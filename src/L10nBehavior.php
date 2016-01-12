<?php
namespace gossi\propel\behavior\l10n;

use Propel\Generator\Behavior\I18n\I18nBehavior;

class L10nBehavior extends I18nBehavior {

	// default parameters value
	protected $parameters = [
		'i18n_table'        => '%TABLE%_i18n',
		'i18n_phpname'      => '%PHPNAME%I18n',
		'i18n_columns'      => '',
		'i18n_pk_column'    => null,
		'locale_column'     => 'locale',
		'locale_length'     => 76,
		'locale_alias'      => '',
	];
	
	protected $templateDirnameBackup;

	public function __construct() {
		parent::__construct();

		$r = new \ReflectionObject(new I18nBehavior());
		$this->dirname = dirname($r->getFileName());
	}
	
	public function modifyDatabase() {
		// override parent behavior... but do nothing
	}
	
	public function staticAttributes($builder) {
		// override parent behavior... but do nothing
	}
	
	public function getDefaultLocale() {
		return PropelL10n::getLocale();
	}

	public function getObjectBuilderModifier() {
		if (null === $this->objectBuilderModifier) {
			$this->objectBuilderModifier = new L10nBehaviorObjectBuilderModifier($this);
		}
	
		return $this->objectBuilderModifier;
	}
	
	public function getQueryBuilderModifier() {
		if (null === $this->queryBuilderModifier) {
			$this->queryBuilderModifier = new L10nBehaviorQueryBuilderModifier($this);
		}
	
		return $this->queryBuilderModifier;
	}
	
	public function backupTemplatesDirname() {
		$this->templateDirnameBackup = $this->dirname;
		
		$r = new \ReflectionObject($this);
		$this->dirname = dirname($r->getFileName());
	}
	
	public function restoreTemplatesDirname() {
		$this->dirname = $this->templateDirnameBackup;
	}

}
