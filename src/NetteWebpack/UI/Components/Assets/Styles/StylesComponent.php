<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\UI\Components\Assets\Styles;

trait StylesComponent
{

	private ControlFactory $stylesControlFactory;

	public function injectStylesControlFactory(ControlFactory $controlFactory): void
	{
		$this->stylesControlFactory = $controlFactory;
	}

	public function getStylesComponent(): Control
	{
		return $this['styles'];
	}

	protected function createComponentStyles(): Control
	{
		return $this->stylesControlFactory->create();
	}

	protected function attachComponentStyles(Control $component): void
	{
		$this->addComponent($component, 'styles');
	}

}
