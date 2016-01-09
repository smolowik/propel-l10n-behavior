<?php
namespace gossi\propel\behavior\l10n\tests\model;

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
	}
	
	public function testDefaultLocale() {
		$p = new \Product();
		
		$this->assertNull($p->getLocale());
	}
}