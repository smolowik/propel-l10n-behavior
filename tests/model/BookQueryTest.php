<?php
namespace gossi\propel\behavior\l10n\tests;

use gossi\propel\behavior\l10n\PropelL10n;
use Propel\Generator\Util\QuickBuilder;

class BookQueryTest extends \PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass() {
		if (!class_exists('\Book')) {
			$schema = <<<EOF
<database name="l10n_behavior">
	<table name="book">
		<column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
		<column name="title" type="VARCHAR" required="true" />
		<column name="author" type="VARCHAR" />
	
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
		// reset db contents
		\BookQuery::create()->deleteAll();
		\BookI18nQuery::create()->deleteAll();
		
		// fill in some dummy data
		
		// lord of the rings
		$b = new \Book();
		$b->setTitle('Lord of the Rings');
		$b->setTitle('Herr der Ringe', 'de');
		$b->setTitle('Yubiwa Monogatari', 'ja-latn-JP');
		$b->save();
		
		// harry potter
		$b = new \Book();
		$b->setTitle('Harry Potter and the Philosopher\'s Stone');
		$b->setTitle('Harry Potter und der Stein der Weisen', 'de');
		$b->setTitle('Harī Pottā to kenja no ishi', 'ja-latn-JP');
		$b->save();
		
		$b = new \Book();
		$b->setTitle('Harry Potter and the Prisoner of Azkaban');
		$b->setTitle('Harry Potter und der Gefangene von Askaban', 'de');
		$b->setTitle('Harī Pottā to Azukaban no shūjin', 'ja-latn-JP');
		$b->save();
	}

	public function testFilter() {
		$q = \BookQuery::create();
		$q->filterByTitle('Lord of the Rings');
		$b = $q->findOne();
		
		$this->assertNotNull($b);
		$this->assertEquals('Herr der Ringe', $b->getTitle('de'));
	}
	
	public function testFind() {
		$q = \BookQuery::create();
		$books = $q->findByTitle('Harry Potter%');
		
		$this->assertEquals(2, count($books));
	}
	
	public function testFindOne() {
		$q = \BookQuery::create();
		$b = $q->findOneByTitle('Harry Potter%');
	
		$this->assertNotNull($b);
		$this->assertEquals('Harry Potter und der Stein der Weisen', $b->getTitle('de'));
	}
	
	public function testLocales() {
		$q = \BookQuery::create();
		$q->setLocale('de');
		$q->filterByTitle('Herr der Ringe');
		$b = $q->findOne();
		
		$this->assertNotNull($b);
		
		$q = \BookQuery::create();
		$q->setLocale('de');
		$q->filterByTitle('Yubiwa Monogatari', null, 'ja-latn-JP');
		$b = $q->findOne();
		
		$this->assertNotNull($b);
	}
}