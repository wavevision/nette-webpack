<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests;

use Wavevision\NetteWebpack\DevServer;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\NetteWebpack\LoadManifest;
use Wavevision\NetteWebpack\WebpackParameters;

class WebpackParametersTest extends DIContainerTestCase
{

	public function testGetAsset(): void
	{
		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage("Webpack asset 'test' does not exist.");
		$this->getService()->getAsset('test');
	}

	public function testGetBasePath(): void
	{
		$this->assertEquals('http://localhost:9006', $this->createService()->getBasePath());
	}

	public function testGetChunks(): void
	{
		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage("Invalid webpack manifest format, property 'chunks' is missing.");
		$this->createService()->getChunks();
	}

	public function testGetDir(): void
	{
		$this->assertStringContainsString('/examples/www/dist', $this->getService()->getDir());
	}

	public function testGetEntryChunks(): void
	{
		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage("Webpack chunks for entry 'test' do not exist.");
		$this->getService()->getEntryChunks('test');
	}

	public function testIsProduction(): void
	{
		$this->assertTrue($this->getService()->isProduction());
		$this->assertFalse($this->createService()->isProduction());
	}

	private function createService(): WebpackParameters
	{
		$webpackParameters = new WebpackParameters(
			new DevServer([DevServer::ENABLED => true, DevServer::URL => 'http://localhost:9006']),
			'/dist',
			'dist',
			[],
			false,
		);
		$loadManifest = $this->createMock(LoadManifest::class);
		$loadManifest
			->expects($this->atMost(1))
			->method('process')
			->willReturn([]);
		$webpackParameters->injectLoadManifest($loadManifest);
		return $webpackParameters;
	}

	private function getService(): WebpackParameters
	{
		/** @var WebpackParameters $webpackParameters */
		$webpackParameters = $this->getContainer()->getByType(WebpackParameters::class);
		return $webpackParameters;
	}

}
