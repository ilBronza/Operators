<?php

namespace IlBronza\Operators\Tests\Feature;

use IlBronza\CRUD\Traits\Model\CRUDArchiverTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Operators\Services\ContracttypeMerger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class ContracttypeMergerTest extends TestCase
{
	protected function defineEnvironment($app)
	{
		$app['config']->set('database.default', 'testing');
		$app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:']);
		$app['config']->set('operators.models.contracttype.class', MergeContracttype::class);
		$app['config']->set('operators.models.operatorContracttype.class', MergeAssociation::class);
	}

	protected function setUp() : void
	{
		parent::setUp();
		// The isolated service tests do not boot CRUD's UI providers.
		require_once dirname((new \ReflectionClass(\IlBronza\CRUD\CRUDServiceProvider::class))->getFileName()) . '/helpers.php';
		Schema::create('merge_contracttypes', function (Blueprint $table) : void
		{
			$table->string('id')->primary();
			$table->string('name');
			$table->timestamp('archived_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
		Schema::create('merge_operators', function (Blueprint $table) : void
		{
			$table->string('id')->primary();
			$table->softDeletes();
		});
		Schema::create('merge_associations', function (Blueprint $table) : void
		{
			$table->string('id')->primary();
			$table->string('contracttype_id');
			$table->string('operator_id');
			$table->integer('rate')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
		foreach (['master', 'slave-a', 'slave-b'] as $id)
			DB::table('merge_contracttypes')->insert(['id' => $id, 'name' => $id]);
		foreach (['shared', 'new', 'deleted', 'unlinked'] as $id)
			DB::table('merge_operators')->insert(['id' => $id, 'deleted_at' => $id === 'deleted' ? now() : null]);
		foreach ([['master', 'shared'], ['slave-a', 'shared'], ['slave-a', 'new'], ['slave-b', 'new'], ['slave-b', 'deleted'], ['slave-b', 'unlinked']] as [$contracttype, $operator])
			MergeAssociation::create([
				'contracttype_id' => $contracttype, 'operator_id' => $operator, 'rate' => 100,
				'deleted_at' => $operator === 'unlinked' ? now() : null,
			]);
	}

	public function test_preview_uses_configured_models_and_counts_each_operator_once() : void
	{
		$preview = app(ContracttypeMerger::class)->preview(['master', 'slave-a', 'slave-b']);
		$this->assertSame(2, $preview['selectedOperatorCount']);
		$this->assertSame([
			'slave_count' => 2, 'slave_operators' => 2, 'master_operators' => 1,
			'already_associated' => 1, 'new_associations' => 1, 'final_operators' => 2,
		], $preview['summaries']['master']);
		$this->assertSame(3, MergeContracttype::count());
		$this->assertSame(1, MergeAssociation::where('contracttype_id', 'master')->count());
	}

	public function test_merge_adds_associations_and_archives_without_changing_history_or_rates() : void
	{
		$oldRows = DB::table('merge_associations')->get();
		$result = app(ContracttypeMerger::class)->merge(['master', 'slave-a', 'slave-b'], 'master');
		$this->assertSame(1, $result['created']);
		$this->assertSame(2, $result['archived']);
		$this->assertSame(['master'], MergeContracttype::pluck('id')->all());
		$this->assertSame(0, DB::table('merge_contracttypes')->whereNotNull('deleted_at')->count());
		$this->assertSame(2, DB::table('merge_contracttypes')->whereNotNull('archived_at')->count());
		$this->assertSame(2, MergeAssociation::where('contracttype_id', 'master')->count());
		$this->assertNull(MergeAssociation::where('contracttype_id', 'master')->where('operator_id', 'new')->value('rate'));
		foreach ($oldRows as $row)
			$this->assertEquals($row, DB::table('merge_associations')->where('id', $row->id)->first());
	}

	public function test_one_slave_can_use_a_master_outside_the_selection() : void
	{
		$result = app(ContracttypeMerger::class)->merge(['slave-a'], 'master');
		$this->assertSame(1, $result['created']);
		$this->assertSame(1, $result['archived']);
		$this->assertNotNull(MergeContracttype::find('slave-b'));
	}

	public function test_archive_failure_rolls_back_the_entire_merge() : void
	{
		MergeContracttype::saving(function ($model) : void
		{
			if ($model->id === 'slave-b') throw new \RuntimeException('Archive failed');
		});
		try
		{
			app(ContracttypeMerger::class)->merge(['slave-a', 'slave-b'], 'master');
			$this->fail('The archive should fail.');
		}
		catch (\RuntimeException $exception)
		{
			$this->assertSame('Archive failed', $exception->getMessage());
		}
		$this->assertSame(1, MergeAssociation::where('contracttype_id', 'master')->count());
		$this->assertSame(3, MergeContracttype::count());
	}

	public function test_a_stale_selection_is_rejected_before_any_write() : void
	{
		DB::table('merge_contracttypes')->where('id', 'slave-a')->update(['archived_at' => now()]);
		try
		{
			app(ContracttypeMerger::class)->merge(['slave-a', 'slave-b'], 'master');
			$this->fail('Archived selections must be rejected.');
		}
		catch (ValidationException $exception)
		{
			$this->assertArrayHasKey('ids', $exception->errors());
		}
		$this->assertSame(1, MergeAssociation::where('contracttype_id', 'master')->count());
		$this->assertNotNull(MergeContracttype::find('slave-b'));
	}
}

class MergeContracttype extends Model
{
	use SoftDeletes, CRUDArchiverTrait;
	protected $table = 'merge_contracttypes';
	protected $keyType = 'string';
	public function getName() { return $this->name; }
	public function userCanUpdate($user = null) { return true; }
	public function userCanDelete($user = null) { return true; }
}

class MergeAssociation extends Model
{
	use SoftDeletes, CRUDUseUuidTrait;
	protected $table = 'merge_associations';
	protected $keyType = 'string';
	protected $guarded = [];
	public function operator() { return $this->belongsTo(MergeOperator::class, 'operator_id'); }
}

class MergeOperator extends Model
{
	use SoftDeletes;
	protected $table = 'merge_operators';
	protected $keyType = 'string';
}
