<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests;

use Wavevision\NetteWebpack\FormatAssetName;
use Wavevision\NetteWebpack\FormatChunkName;
use Wavevision\NetteWebpack\NetteWebpack;
use Wavevision\Utils\ContentTypes;

class FormatChunkNameTest extends UnitTestCase
{

	public function testFormatScript(): void
	{
		$this->assertEquals('script.js', $this->createService()->formatScript('script'));
	}

	public function testFormatStyle(): void
	{
		$this->assertEquals('style.css', $this->createService()->formatStyle('style'));
	}

	public function testProcess(): void
	{
		$this->assertEquals('chunk.js', $this->createService()->process('chunk.js', ContentTypes::JS));
	}

	private function createService(): FormatChunkName
	{
		$service = new FormatChunkName();
		$netteWebpack = $this->createMock(NetteWebpack::class);
		$netteWebpack
			->expects($this->once())
			->method('getUrl')
			->willReturnArgument(0);
		$netteWebpack
			->expects($this->once())
			->method('getAsset')
			->willReturnArgument(0);
		$formatAssetName = new FormatAssetName();
		$formatAssetName->injectNetteWebpack($netteWebpack);
		$service->injectFormatAssetName($formatAssetName);
		return $service;
	}

}
