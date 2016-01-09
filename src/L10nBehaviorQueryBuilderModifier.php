<?php
namespace gossi\propel\behavior\l10n;

use Propel\Generator\Behavior\I18n\I18nBehaviorQueryBuilderModifier;

class L10nBehaviorQueryBuilderModifier extends I18nBehaviorQueryBuilderModifier {

	public function queryMethods($builder) {
		$builder->declareClass('gossi\\propel\\behavior\\l10n\\PropelL10n');
		return parent::queryMethods($builder);
	}
	
	protected function addJoinI18n() {
		$fk = $this->behavior->getI18nForeignKey();
	
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('queryJoinI18n', [
			'queryClass'       => $this->builder->getQueryClassName(),
			'i18nRelationName' => $this->builder->getRefFKPhpNameAffix($fk),
			'localeColumn'     => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}

	protected function addJoinWithI18n() {
		$fk = $this->behavior->getI18nForeignKey();
	
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('queryJoinWithI18n', [
			'queryClass'       => $this->builder->getQueryClassName(),
			'i18nRelationName' => $this->builder->getRefFKPhpNameAffix($fk),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}

	protected function addUseI18nQuery() {
		$i18nTable = $this->behavior->getI18nTable();
		$fk = $this->behavior->getI18nForeignKey();
	
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('queryUseI18nQuery', [
			'queryClass'           => $this->builder->getClassNameFromBuilder($this->builder->getNewStubQueryBuilder($i18nTable)),
			'namespacedQueryClass' => $this->builder->getNewStubQueryBuilder($i18nTable)->getFullyQualifiedClassName(),
			'i18nRelationName'     => $this->builder->getRefFKPhpNameAffix($fk),
			'localeColumn'         => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
}
