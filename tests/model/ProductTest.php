<?php
namespace gossi\propel\behavior\l10n\tests\model;

use gossi\propel\behavior\l10n\PropelL10n;

class ProductTest extends ModelTestCase {
	
	protected function setUp() {
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

		$this->getBuilder($schema)->build();
		
		PropelL10n::setLocale('en'); // just reset, may changed in other tests
		PropelL10n::setFallback('en');
		PropelL10n::setDependencies([
			'de-CH' => 'de-DE',
			'de-AT' => 'de-DE',
			'de-DE' => 'en-US',
			'ja-JP' => 'en-US',
			'en-US' => 'en'
		]);
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

}