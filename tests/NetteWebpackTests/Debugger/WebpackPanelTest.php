<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests\Debugger;

use Wavevision\NetteWebpack\Debugger\WebpackPanel;
use Wavevision\NetteWebpack\NetteWebpackParameters;
use Wavevision\NetteWebpackTests\DIContainerTestCase;

class WebpackPanelTest extends DIContainerTestCase
{

	public function testGetTab(): void
	{
		$this->assertIsString($this->createPanel()->getTab());
	}

	public function testGetPanel(): void
	{
		$this->assertIsString($this->createPanel()->getPanel());
	}

	private function createPanel(): WebpackPanel
	{
		return new WebpackPanel($this->getContainer()->getByType(NetteWebpackParameters::class));
	}

}
