<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests;

use Wavevision\NetteWebpack\DevServer;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\NetteWebpack\LoadManifest;
use Wavevision\NetteWebpack\NetteWebpack;

class NetteWebpackTest extends DIContainerTestCase
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

	public function testGetResolvedAssets(): void
	{
		$this->assertEquals([], $this->getService()->getResolvedAssets());
	}

	public function testGetResolvedAssetsWithoutEntryChunks(): void
	{
		$service = $this->getService();
		$service->getAsset('images/image.png');
		$service->getEntryChunks('entry');
		$this->assertContains('images/image.png', array_keys($service->getResolvedAssetsWithoutEntryChunks()));
	}

	public function testIsProduction(): void
	{
		$this->assertTrue($this->getService()->isProduction());
		$this->assertFalse($this->createService()->isProduction());
	}

	private function createService(): NetteWebpack
	{
		$webpackParameters = new NetteWebpack(
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

	private function getService(): NetteWebpack
	{
		/** @var NetteWebpack $webpackParameters */
		$webpackParameters = $this->getContainer()->getByType(NetteWebpack::class);
		return $webpackParameters;
	}

}
