<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests;

use Wavevision\NetteWebpack\FormatChunkName;
use Wavevision\NetteWebpack\WebpackParameters;
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
		$webpackParameters = $this->createMock(WebpackParameters::class);
		$webpackParameters
			->expects($this->once())
			->method('getUrl')
			->willReturnArgument(0);
		$webpackParameters
			->expects($this->once())
			->method('getAsset')
			->willReturnArgument(0);
		$service->injectWebpackParameters($webpackParameters);
		return $service;
	}

}
