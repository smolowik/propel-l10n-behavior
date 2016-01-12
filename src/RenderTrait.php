<?php
namespace gossi\propel\behavior\l10n;

trait RenderTrait {
	
	protected function renderTemplate($name, $vars = []) {
		$this->behavior->backupTemplatesDirname();
		$template = $this->behavior->renderTemplate($name, $vars);
		$this->behavior->restoreTemplatesDirname();
		return $template;
	}
}