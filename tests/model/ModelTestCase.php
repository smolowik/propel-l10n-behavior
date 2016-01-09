<?php
namespace gossi\propel\behavior\l10n\tests\model;

use Propel\Generator\Util\QuickBuilder;

class ModelTestCase extends \PHPUnit_Framework_TestCase {
	
	protected function getBuilder($schema) {
		$builder = new QuickBuilder();
		$builder->setSchema($schema);
		return $builder;
	}
	
}
