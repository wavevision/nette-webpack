<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\UI\Components\Assets;

trait AssetsComponent
{

	private ControlFactory $assetsControlFactory;

	public function injectAssetsControlFactory(ControlFactory $controlFactory): void
	{
		$this->assetsControlFactory = $controlFactory;
	}

	public function getAssetsComponent(): Control
	{
		return $this['assets'];
	}

	protected function createComponentAssets(): Control
	{
		return $this->assetsControlFactory->create();
	}

	protected function attachComponentAssets(Control $component): void
	{
		$this->addComponent($component, 'assets');
	}

}
