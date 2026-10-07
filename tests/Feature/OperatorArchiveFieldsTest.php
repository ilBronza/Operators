<?php

namespace IlBronza\Operators\Tests\Feature;

use IlBronza\Operators\Http\Controllers\Operators\OperatorArchiveController;
use IlBronza\Operators\Http\Controllers\Parameters\Datatables\OperatorArchiveFieldsGroupParametersFile;
use IlBronza\UikitTemplate\UikitTemplate;
use Orchestra\Testbench\TestCase;
use ReflectionClass;

class OperatorArchiveFieldsTest extends TestCase
{
	protected function defineEnvironment($app)
	{
		$app['config']->set('operators', require __DIR__ . '/../../config/operators.php');
		$app->instance('uikittemplate', new UikitTemplate());
	}

	protected function archiveFields() : array
	{
		$controller = (new ReflectionClass(OperatorArchiveController::class))->newInstanceWithoutConstructor();

		return $controller->getIndexFieldsArray();
	}

	public function test_package_configuration_loads_and_traces_archive_fields() : void
	{
		$this->assertSame(OperatorArchiveFieldsGroupParametersFile::getFieldsGroup(), $this->archiveFields());
		$this->assertSame([OperatorArchiveFieldsGroupParametersFile::class], app('uikittemplate')->getFieldsGroupsNames());
	}

	public function test_unqualified_archive_class_from_published_configuration_loads_fields() : void
	{
		config(['operators.models.operator.fieldsGroupsFiles.archive' => 'OperatorArchiveFieldsGroupParametersFile']);

		$this->assertSame(OperatorArchiveFieldsGroupParametersFile::getFieldsGroup(), $this->archiveFields());
		$this->assertSame([OperatorArchiveFieldsGroupParametersFile::class], app('uikittemplate')->getFieldsGroupsNames());
	}

	public function test_custom_archive_fields_configuration_is_preserved() : void
	{
		config(['operators.models.operator.fieldsGroupsFiles.archive' => CustomArchiveFields::class]);

		$this->assertSame(['fields' => ['custom_name' => 'flat']], $this->archiveFields());
		$this->assertSame([CustomArchiveFields::class], app('uikittemplate')->getFieldsGroupsNames());
	}
}

class CustomArchiveFields extends OperatorArchiveFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return ['fields' => ['custom_name' => 'flat']];
	}
}
