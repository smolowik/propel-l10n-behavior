<?php
namespace gossi\propel\behavior\l10n;

use Propel\Generator\Behavior\I18n\I18nBehaviorQueryBuilderModifier;
use Propel\Generator\Model\Column;

class L10nBehaviorQueryBuilderModifier extends I18nBehaviorQueryBuilderModifier {
	
	use RenderTrait;

	public function queryMethods($builder) {
		$builder->declareClass('gossi\\propel\\behavior\\l10n\\PropelL10n');
		$script = parent::queryMethods($builder);
		$script .= $this->objectAttributes();
		$script .= $this->addSetLocale();
		$script .= $this->addGetLocale();
		
		if ($alias = $this->behavior->getParameter('locale_alias')) {
			$script .= $this->addGetLocaleAlias($alias);
			$script .= $this->addSetLocaleAlias($alias);
		}
		
		foreach ($this->behavior->getI18nColumns() as $column) {
			$script .= $this->addFilter($column);
			$script .= $this->addFind($column);
			$script .= $this->addFindOne($column);
		}
		return $script;
	}
	
	protected function objectAttributes() {
		return $this->renderTemplate('queryAttributes');
	}
	
	protected function addFilter(Column $column) {
		return $this->renderTemplate('queryFilter', [
			'queryClass'		=> $this->builder->getQueryClassName(),
			'columnPhpName'     => $column->getPhpName(),
			'columnName'		=> $column->getName(),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addFind(Column $column) {
		return $this->renderTemplate('queryFind', [
			'objectClassName'	=> $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($column->getTable())),
			'columnPhpName'     => $column->getPhpName(),
			'columnName'		=> $column->getName(),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addFindOne(Column $column) {
		return $this->renderTemplate('queryFindOne', [
			'objectClassName'	=> $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($column->getTable())),
			'columnPhpName'     => $column->getPhpName(),
			'columnName'		=> $column->getName(),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addSetLocale() {
		return $this->renderTemplate('objectSetLocale', [
			'objectClassName'   => $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($this->table)),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addGetLocale() {
		return $this->renderTemplate('objectGetLocale', [
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addSetLocaleAlias($alias) {
		return $this->renderTemplate('objectSetLocaleAlias', [
			'objectClassName'  => $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($this->table)),
			'alias'            => ucfirst($alias),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addGetLocaleAlias($alias) {
		return $this->renderTemplate('objectGetLocaleAlias', [
			'alias' => ucfirst($alias),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addJoinI18n() {
		$fk = $this->behavior->getI18nForeignKey();
	
		return $this->renderTemplate('queryJoinI18n', [
			'queryClass'       => $this->builder->getQueryClassName(),
			'i18nRelationName' => $this->builder->getRefFKPhpNameAffix($fk),
			'localeColumn'     => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}

	protected function addJoinWithI18n() {
		$fk = $this->behavior->getI18nForeignKey();
	
		return $this->renderTemplate('queryJoinWithI18n', [
			'queryClass'       => $this->builder->getQueryClassName(),
			'i18nRelationName' => $this->builder->getRefFKPhpNameAffix($fk),
		]);
	}

	protected function addUseI18nQuery() {
		$i18nTable = $this->behavior->getI18nTable();
		$fk = $this->behavior->getI18nForeignKey();
	
		return $this->renderTemplate('queryUseI18nQuery', [
			'queryClass'           => $this->builder->getClassNameFromBuilder($this->builder->getNewStubQueryBuilder($i18nTable)),
			'namespacedQueryClass' => $this->builder->getNewStubQueryBuilder($i18nTable)->getFullyQualifiedClassName(),
			'i18nRelationName'     => $this->builder->getRefFKPhpNameAffix($fk),
			'localeColumn'         => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
}
