<?php
namespace gossi\propel\behavior\l10n\tests\model;

use gossi\propel\behavior\l10n\PropelL10n;
use Propel\Generator\Util\QuickBuilder;

class ProductTest extends \PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass() {
		if (!class_exists('\Product')) {
			$schema = <<<EOF
<database name="l10n_behavior">
	<table name="product">
		<column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
		<column name="title" type="VARCHAR" required="true" />
		
		<behavior name="l10n">
			<parameter name="i18n_columns" value="title" />
		</behavior>
	</table>
</database>
EOF;

			QuickBuilder::buildSchema($schema);
		}
		
		PropelL10n::setLocale('en'); // just reset, may changed in other tests
		PropelL10n::setFallback('en');
		PropelL10n::setDependencies([
			'de-CH' => 'de-DE',
			'de-AT' => 'de-DE',
			'de-DE' => 'en-US',
			'ja' => 'en-US'
		]);
	}
	
	protected function setUp() {
		\ProductQuery::create()->deleteAll();
		\ProductI18nQuery::create()->deleteAll();
	}
	
	public function testDefaultLocale() {
		$p = new \Product();
		
		$this->assertNull($p->getLocale());
		
		$p->setTitle('delicious');
		$this->assertEquals('delicious', $p->getTitle());
		$this->assertEquals('en', $p->getCurrentTranslation()->getLocale());
	}
	
	public function testDependency() {
		$p = new \Product();
		$p->setLocale('de-DE');
		$p->setTitle('lecker');
// 		$p->setLocale('de-CH');
// 		$p->setTitle('lecker, odr?');
		
		$this->assertEquals('lecker', $p->getTitle('de-CH'));
	}

	public function testPrimaryLanguage() {
		$p = new \Product();
		$p->setLocale('ja');
		$p->setTitle('おいしい');
		
		$this->assertEquals('おいしい', $p->getTitle('ja-JP'));
	}

	public function testFallback() {
		$p = new \Product();
		$p->setLocale('en');
		$p->setTitle('delicious');
		$p->setLocale('de');
		$p->setTitle('lecker');

		$this->assertEquals('delicious', $p->getTitle('it'));
	}
	
	public function testSetterLocale() {
		$p = new \Product();
		$p->setTitle('delicious', 'en');
		$p->setTitle('bene', 'it');

		$this->assertEquals('delicious', $p->getTitle('en-US'));
		$this->assertEquals('bene', $p->getTitle('it-IT'));
	}
	
	public function testLocaleTagChain() {
		PropelL10n::addDependency('it-IT', 'en');
		$p = new \Product();
		$p->setTitle('bene', 'it');
		$p->setTitle('good', 'en');
		
		$this->assertEquals('bene', $p->getTitle('it-IT'));
	}
}