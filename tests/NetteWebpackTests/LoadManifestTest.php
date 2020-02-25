<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests;

use Wavevision\NetteWebpack\DevServer;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\NetteWebpack\LoadManifest;

class LoadManifestTest extends UnitTestCase
{

	public function testProcess(): void
	{
		$loadManifest = $this->createService();
		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage('Unable to load webpack manifest file.');
		$loadManifest->process();
	}

	private function createService(): LoadManifest
	{
		return new LoadManifest(
			new DevServer([DevServer::ENABLED => true, DevServer::URL => 'http://localhost:9006']),
			'/dist',
			'dist',
			'manifest.json'
		);
	}

}
