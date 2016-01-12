
<?php echo $comment ?>

<?php echo $visibility ?> function set<?php echo $columnPhpName?>(<?php echo $typeHint ?>, $locale = null<?php 
if ($column->isLazyLoad()) {
 	echo ", ConnectionInterface \$con = null";
}
?>)
{
	if ($locale === null) {
		$locale = $this->get<?= $localeColumnName ?>();
	}
	if ($locale === null) {
		$locale = PropelL10n::getLocale();
	}
    $this->getTranslation($locale)->set<?php echo $columnPhpName ?>($v<?php if ($column->isLazyLoad()) echo ', $con';?>);

    return $this;
}
