<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\UI\Components\Assets\Scripts;

trait ScriptsComponent
{

	private ControlFactory $scriptsControlFactory;

	public function injectScriptsControlFactory(ControlFactory $controlFactory): void
	{
		$this->scriptsControlFactory = $controlFactory;
	}

	public function getScriptsComponent(): Control
	{
		return $this['scripts'];
	}

	protected function createComponentScripts(): Control
	{
		return $this->scriptsControlFactory->create();
	}

	protected function attachComponentScripts(Control $component): void
	{
		$this->addComponent($component, 'scripts');
	}

}
