<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests;

use Wavevision\NetteWebpack\DevServer;

class DevServerTest extends UnitTestCase
{

	public function testGetUrl(): void
	{
		$this->assertEquals(
			'http://localhost:9006',
			(new DevServer([DevServer::ENABLED => false, DevServer::URL => 'http://localhost:9006']))->getUrl()
		);
	}

}
