<?php
namespace gossi\propel\behavior\l10n;

use Propel\Generator\Behavior\I18n\I18nBehaviorObjectBuilderModifier;
use Propel\Generator\Model\Column;

class L10nBehaviorObjectBuilderModifier extends I18nBehaviorObjectBuilderModifier {
	
	use RenderTrait;
	
	public function objectMethods($builder) {
		$builder->declareClass('gossi\\propel\\behavior\\l10n\\PropelL10n');
		return parent::objectMethods($builder);
	}
	
	public function objectAttributes($builder) {
		return $this->renderTemplate('objectAttributes', [
			'objectClassName' => $builder->getClassNameFromBuilder($builder->getNewStubObjectBuilder($this->behavior->getI18nTable())),
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
	
	protected function addGetTranslation() {
		$plural = false;
		$i18nTable = $this->behavior->getI18nTable();
		$fk = $this->behavior->getI18nForeignKey();
	
		return $this->renderTemplate('objectGetTranslation', [
			'i18nTablePhpName' => $this->builder->getClassNameFromBuilder($this->builder->getNewStubObjectBuilder($i18nTable)),
			'i18nListVariable' => $this->builder->getRefFKCollVarName($fk),
			'localeColumnName' => $this->behavior->getLocaleColumn()->getPhpName(),
			'i18nQueryName'    => $this->builder->getClassNameFromBuilder($this->builder->getNewStubQueryBuilder($i18nTable)),
			'i18nSetterMethod' => $this->builder->getRefFKPhpNameAffix($fk, $plural),
		]);
	}
	
	protected function addRemoveTranslation() {
		$i18nTable = $this->behavior->getI18nTable();
		$fk = $this->behavior->getI18nForeignKey();
	
		return $this->renderTemplate('objectRemoveTranslation', [
			'objectClassName' => $this->builder->getClassNameFromBuilder($this->builder->getStubObjectBuilder($this->table)),
			'i18nQueryName'    => $this->builder->getClassNameFromBuilder($this->builder->getNewStubQueryBuilder($i18nTable)),
			'i18nCollection'   => $this->builder->getRefFKCollVarName($fk),
			'localeColumnName' => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addGetCurrentTranslation() {
		return $this->renderTemplate('objectGetCurrentTranslation', [
			'i18nTablePhpName' => $this->builder->getClassNameFromBuilder($this->builder->getNewStubObjectBuilder($this->behavior->getI18nTable())),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName(),
		]);
	}
	
	protected function addTranslatedColumnGetter(Column $column) {
		$objectBuilder = $this->builder->getNewObjectBuilder($this->behavior->getI18nTable());
		$comment = '';
		if ($this->isDateType($column->getType())) {
			$objectBuilder->addTemporalAccessorComment($comment, $column);
		} else {
			$objectBuilder->addDefaultAccessorComment($comment, $column);
		}
		$comment = preg_replace('/^\t/m', '', $comment);

		return $this->renderTemplate('objectTranslatedColumnGetter', [
			'comment'           => $comment,
			'column'			=> $column,
			'columnPhpName'     => $column->getPhpName(),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName()
		]);
	}
	
	protected function addTranslatedColumnSetter(Column $column) {
		$visibility = $column->getTable()->isReadOnly() ? 'protected' : $column->getMutatorVisibility();
		
		$typeHint = '';
		$null = '';
		
		if ($column->getTypeHint()) {
			$typeHint = $column->getTypeHint();
			if ('array' !== $typeHint) {
				$typeHint = $this->declareClass($typeHint);
			}
		
			$typeHint .= ' ';
		
			if (!$column->isNotNull()) {
				$null = ' = null';
			}
		}
		
		$typeHint = "$typeHint\$v$null";
		
		
		$i18nTablePhpName = $this->builder->getClassNameFromBuilder($this->builder->getNewStubObjectBuilder($this->behavior->getI18nTable()));
		$tablePhpName = $this->builder->getObjectClassName();
		$objectBuilder = $this->builder->getNewObjectBuilder($this->behavior->getI18nTable());
		$comment = '';
		if ($this->isDateType($column->getType())) {
			$objectBuilder->addTemporalMutatorComment($comment, $column);
		} else {
			$objectBuilder->addMutatorComment($comment, $column);
		}
		$comment = preg_replace('/^\t/m', '', $comment);
		$comment = str_replace('@return     $this|' . $i18nTablePhpName, '@return     $this|' . $tablePhpName, $comment);
		
        return $this->renderTemplate('objectTranslatedColumnSetter', [
			'comment'           => $comment,
			'column'			=> $column,
        	'visibility'		=> $visibility,
        	'typeHint'			=> $typeHint,
			'columnPhpName'     => $column->getPhpName(),
			'localeColumnName'  => $this->behavior->getLocaleColumn()->getPhpName()
        ]);
    }

	public function objectClearReferences($builder) {
		return $this->renderTemplate('objectClearReferences');
	}

}
