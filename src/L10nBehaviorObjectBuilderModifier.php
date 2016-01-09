<?php
namespace gossi\propel\behavior\l10n;

use Propel\Generator\Behavior\I18n\I18nBehaviorObjectBuilderModifier;

class L10nBehaviorObjectBuilderModifier extends I18nBehaviorObjectBuilderModifier {
	
	public function objectMethods($builder) {
		$builder->declareClass('gossi\\propel\\behavior\\l10n\\PropelL10n');
		return parent::objectMethods($builder);
	}
	
	public function objectAttributes($builder) {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectAttributes', [
			'objectClassName' => $builder->getClassNameFromBuilder($builder->getNewStubObjectBuilder($this->behavior->getI18nTable())),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
	
	protected function addSetLocale() {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectSetLocale', [
			'objectClassName'   => $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($this->table)),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}

	protected function addGetLocale() {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectGetLocale', [
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
	
	protected function addSetLocaleAlias($alias) {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectSetLocaleAlias', [
			'objectClassName'  => $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($this->table)),
			'alias'            => ucfirst($alias),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
	
	protected function addGetLocaleAlias($alias) {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectGetLocaleAlias', [
			'alias' => ucfirst($alias),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
	
	protected function addGetTranslation() {
		$plural = false;
		$i18nTable = $this->behavior->getI18nTable();
		$fk = $this->behavior->getI18nForeignKey();
	
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectGetTranslation', [
			'i18nTablePhpName' => $this->builder->getClassNameFromBuilder($this->builder->getNewStubObjectBuilder($i18nTable)),
			'defaultLocale'    => $this->behavior->getDefaultLocale(),
			'i18nListVariable' => $this->builder->getRefFKCollVarName($fk),
			'localeColumnName' => $this->behavior->getLocaleColumn()->getPhpName(),
			'i18nQueryName'    => $this->builder->getClassNameFromBuilder($this->builder->getNewStubQueryBuilder($i18nTable)),
			'i18nSetterMethod' => $this->builder->getRefFKPhpNameAffix($fk, $plural),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
	
	protected function addRemoveTranslation() {
		$i18nTable = $this->behavior->getI18nTable();
		$fk = $this->behavior->getI18nForeignKey();
	
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectRemoveTranslation', [
			'objectClassName' => $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($this->table)),
			'defaultLocale'    => $this->behavior->getDefaultLocale(),
			'i18nQueryName'    => $this->builder->getClassNameFromBuilder($this->builder->getNewStubQueryBuilder($i18nTable)),
			'i18nCollection'   => $this->builder->getRefFKCollVarName($fk),
			'localeColumnName' => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
	
	protected function addGetCurrentTranslation() {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectGetCurrentTranslation', [
			'i18nTablePhpName' => $this->builder->getClassNameFromBuilder($this->builder->getNewStubObjectBuilder($this->behavior->getI18nTable())),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
	
	// TODO: addTranslatedColumnGetter
	// TODO: addTranslatedColumnSetter

	public function objectClearReferences($builder) {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate('objectClearReferences');
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}

}
