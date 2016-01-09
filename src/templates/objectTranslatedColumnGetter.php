
<?php echo $comment ?>
<?php echo $functionStatement ?>

	// when locale dependency exists
	if ($this->currentLocale !== null) {
		$locale = $this->currentLocale;
	}
	if ($locale === null) {
		$locale = PropelL10n::getLocale();
	}
	
	/*if (PropelL10n::hasDependency($locale)) {
		$count = PropelL10n::countDependencies($locale) - 1;
		$column = $this->getCurrentTranslation()->get<?php echo $columnPhpName ?>(<?php echo $params ?>);
		while ($column === null && $count > 0) {
			$locale = PropelL10n::getDepdendency($locale);
			$column = $this->getTranslation($locale)->get<?php echo $columnPhpName ?>(<?php echo $params ?>);
			$count--;
		}
	}*/
	
	// anyway, get default translation
    return $this->getCurrentTranslation()->get<?php echo $columnPhpName ?>(<?php echo $params ?>);
}
