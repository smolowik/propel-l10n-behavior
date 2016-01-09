<?php
namespace gossi\propel\behavior\l10n\tests;

use gossi\propel\behavior\l10n\PropelL10n;

class PropelL10nTest extends \PHPUnit_Framework_TestCase {
	
	public function testAddDependency() {
		PropelL10n::addDependency('de-DE', 'en-US');
		
		$this->assertTrue(PropelL10n::hasDependency('de-DE'));
		$this->assertEquals(['de-DE' => 'en-US'], PropelL10n::getDependencies());
	}
	
	public function testRemoveDepedency() {
		PropelL10n::addDependency('de-DE', 'en-US');
		PropelL10n::removeDependency('de-DE');
		
		$this->assertEquals(0, count(PropelL10n::getDependencies()));
	}
	
	public function testSetDependencies() {
		$deps = [
			'de-DE' => 'en-US',
			'de-CH' => 'de-DE',
			'ja-JP' => 'en-US'
		];
		
		PropelL10n::setDependencies($deps);
		
		$this->assertEquals(2, PropelL10n::countDependencies('de-CH'));
		$this->assertEquals(0, PropelL10n::countDependencies('it-IT'));
		$this->assertEquals($deps, PropelL10n::getDependencies());
	}
	
	public function testCurrentLocale() {
		$this->assertEquals('en', PropelL10n::getLocale());
		
		PropelL10n::setLocale('de-DE');
		$this->assertEquals('de-DE', PropelL10n::getLocale());
	}

}