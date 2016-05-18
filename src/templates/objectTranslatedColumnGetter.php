
<?php echo $comment ?>
<?php echo $comment ?>

<?php echo $column->getAccessorVisibility() ?> function get<?php echo $columnPhpName?>($locale = null<?php 
if ($column->isLazyLoad()) {
 	echo ", ConnectionInterface \$con = null";
}
?>)
{

    return $this->getCurrentTranslation()->get<?php echo $columnPhpName ?>(<?php echo $params ?>);
}
