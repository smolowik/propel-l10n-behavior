
<?php echo $comment ?>

<?php echo $column->getAccessorVisibility() ?> function get<?php echo $columnPhpName?>($locale = null<?php 
 if ($column->isLazyLoad()) {
 	$script .= ", ConnectionInterface \$con = null";
 }
?>)
{
	$value = null;
	if ($locale === null) {
		$locale = $this->get<?= $localeColumnName ?>();
	}
	if ($locale === null) {
		$locale = PropelL10n::getLocale();
	}
	
	// try default locale
	$trans = $this->getTranslation($locale);
	$value = $trans->get<?php echo $columnPhpName ?>(<?php if ($column->isLazyLoad()) echo '$con';?>);
	
	if ($value === null) {
		// try dependency chain
		while (PropelL10n::hasDependency($locale) && $value === null) {
			$locale = PropelL10n::getDependency($locale);
			$trans = $this->getTranslation($locale);
			$value = $trans->get<?php echo $columnPhpName ?>(<?php if ($column->isLazyLoad()) echo '$con';?>);
		}
		
		// try primary language
		if ($value === null) {
			$locale = \Locale::getPrimaryLanguage($locale);
			$trans = $this->getTranslation($locale);
			$value = $trans->get<?php echo $columnPhpName ?>(<?php if ($column->isLazyLoad()) echo '$con';?>);
			
			// try fallback language
			if ($value === null) {
				$locale = PropelL10n::getFallback();
				$trans = $this->getTranslation($locale);
				$value = $trans->get<?php echo $columnPhpName ?>(<?php if ($column->isLazyLoad()) echo '$con';?>);
			}
		}
	}
    return $value;
}
