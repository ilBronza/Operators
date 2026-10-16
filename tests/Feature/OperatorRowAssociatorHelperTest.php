<?php

namespace IlBronza\Operators\Tests\Feature;

use IlBronza\Operators\Helpers\OperatorOrderrows\OperatorRowAssociatorHelper;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\OperatorContracttype;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class OperatorRowAssociatorHelperTest extends TestCase
{
	protected function defineEnvironment($app)
	{
		$app['config']->set('database.default', 'testing');
		$app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:']);
		$app['config']->set('operators', require __DIR__ . '/../../config/operators.php');
		$app['config']->set('operators.models.operator.class', RowAssociatorOperator::class);
		$app['config']->set('operators.models.operator.table', 'row_operators');
		$app['config']->set('operators.models.contracttype.table', 'row_contracttypes');
		$app['config']->set('operators.models.operatorContracttype.class', RowAssociatorAssociation::class);
		$app['config']->set('operators.models.operatorContracttype.table', 'row_associations');
	}

	protected function setUp() : void
	{
		parent::setUp();
		require_once dirname((new \ReflectionClass(\IlBronza\CRUD\CRUDServiceProvider::class))->getFileName()) . '/helpers.php';
		app('translator')->addNamespace('operators', __DIR__ . '/../../resources/lang');
		app()->setLocale('it');

		Schema::create('row_operators', function (Blueprint $table) : void
		{
			$table->string('id')->primary();
			$table->string('display_name')->nullable();
			$table->softDeletes();
		});
		Schema::create('row_contracttypes', function (Blueprint $table) : void
		{
			$table->string('id')->primary();
			$table->string('name')->nullable();
			$table->timestamp('archived_at')->nullable();
			$table->softDeletes();
		});
		Schema::create('row_associations', function (Blueprint $table) : void
		{
			$table->uuid('id')->primary();
			$table->string('operator_id');
			$table->string('contracttype_id');
			$table->integer('cost_company_hour')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
		DB::table('row_operators')->insert(['id' => 'person', 'display_name' => 'Mario Rossi']);
		DB::table('row_contracttypes')->insert(['id' => 'role', 'name' => 'Elettricista']);
	}

	protected function sellable(string $contracttypeId = 'role') : Sellable
	{
		return (new Sellable())->setRawAttributes(['target_id' => $contracttypeId]);
	}

	protected function assertMissingAssociation(string|Operator $operator) : void
	{
		try
		{
			OperatorRowAssociatorHelper::associateRowByParameters(
				new ProductPackageBaseRowcontainerModel(), $operator, $this->sellable()
			);
			$this->fail('A missing association must stop row creation.');
		}
		catch (ValidationException $exception)
		{
			$this->assertSame(422, $exception->status);
			$this->assertSame([
				'operator_id' => ['L’operatore "Mario Rossi" non è ancora associato alla mansione "Elettricista". Associa l’operatore alla mansione prima di aggiungere la riga.'],
			], $exception->errors());
		}
	}

	public function test_missing_association_reports_names_without_writing_by_default() : void
	{
		$this->assertFalse(config('operators.models.operatorRow.createMissingOperatorContracttype'));
		$this->assertMissingAssociation('person');
		$this->assertSame(0, RowAssociatorAssociation::withTrashed()->count());
	}

	public function test_published_config_without_the_new_option_still_requires_the_association() : void
	{
		config(['operators.models.operatorRow' => []]);
		$this->assertMissingAssociation(RowAssociatorOperator::findOrFail('person'));
		$this->assertSame(0, RowAssociatorAssociation::withTrashed()->count());
	}

	public function test_existing_association_is_reused_with_its_costs_for_either_configuration() : void
	{
		$existing = RowAssociatorAssociation::create([
			'operator_id' => 'person', 'contracttype_id' => 'role', 'cost_company_hour' => 45,
		]);

		foreach ([false, true] as $enabled)
		{
			config(['operators.models.operatorRow.createMissingOperatorContracttype' => $enabled]);
			foreach (['person', RowAssociatorOperator::findOrFail('person')] as $operator)
			{
				$association = OperatorRowAssociatorHelper::getOrCreateOperatorContracttype($operator, $this->sellable());
				$this->assertTrue($existing->is($association));
				$this->assertSame(45, $association->cost_company_hour);
			}
		}
		$this->assertSame(1, RowAssociatorAssociation::count());
	}

	public function test_enabled_creation_uses_the_project_model_and_fires_its_events_once() : void
	{
		config(['operators.models.operatorRow.createMissingOperatorContracttype' => true]);
		$created = 0;
		RowAssociatorAssociation::created(function () use (&$created) : void { $created++; });

		$association = OperatorRowAssociatorHelper::getOrCreateOperatorContracttype(
			RowAssociatorOperator::findOrFail('person'), $this->sellable()
		);
		$this->assertInstanceOf(RowAssociatorAssociation::class, $association);
		$this->assertTrue(Str::isUuid($association->getKey()));
		$this->assertSame('person', $association->operator_id);
		$this->assertSame('role', $association->contracttype_id);
		$this->assertNull($association->cost_company_hour);
		$this->assertTrue($association->is(OperatorRowAssociatorHelper::getOrCreateOperatorContracttype('person', $this->sellable())));
		$this->assertSame(1, $created);
		$this->assertSame(1, RowAssociatorAssociation::count());
	}

	public function test_deleted_associations_are_not_reused_or_restored() : void
	{
		$deleted = RowAssociatorAssociation::create(['operator_id' => 'person', 'contracttype_id' => 'role']);
		$deleted->delete();
		$this->assertMissingAssociation('person');

		config(['operators.models.operatorRow.createMissingOperatorContracttype' => true]);
		$association = OperatorRowAssociatorHelper::getOrCreateOperatorContracttype('person', $this->sellable());
		$this->assertNotSame($deleted->getKey(), $association->getKey());
		$this->assertTrue($deleted->fresh()->trashed());
		$this->assertSame(2, RowAssociatorAssociation::withTrashed()->count());
	}

	public function test_creation_rejects_missing_operators_and_contracttypes_before_writing() : void
	{
		config(['operators.models.operatorRow.createMissingOperatorContracttype' => true]);
		foreach ([['missing-person', 'role'], ['person', 'missing-role']] as [$operatorId, $contracttypeId])
		{
			try
			{
				OperatorRowAssociatorHelper::getOrCreateOperatorContracttype($operatorId, $this->sellable($contracttypeId));
				$this->fail('Missing entities must not create an association.');
			}
			catch (ModelNotFoundException $exception)
			{
				$this->assertSame(0, RowAssociatorAssociation::withTrashed()->count());
			}
		}
	}
}

class RowAssociatorOperator extends Operator
{
	protected $with = [];

	public function getName() : ?string
	{
		return $this->display_name;
	}
}

class RowAssociatorAssociation extends OperatorContracttype
{
	// Isolate persistence from UI broadcasts and activity-log infrastructure.
	protected static function bootLogsActivity() : void {}
	public static function bootBroadcastCrudModelEventsTrait() : void {}
}
