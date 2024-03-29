<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests\DI;

use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use Nette\Neon\Neon;
use Nette\Utils\FileSystem;
use Wavevision\NetteWebpack\DI\Extension;
use Wavevision\NetteWebpackExamples\Bootstrap;
use Wavevision\NetteWebpackTests\UnitTestCase;

class ExtensionTest extends UnitTestCase
{

	public function testBeforeCompile(): void
	{
		$extension = $this->createExtension();
		$extension->loadConfiguration();
		$extension->beforeCompile();
		$this->assertNull(null);
	}

	private function createExtension(): Extension
	{
		$extension = new Extension(false);
		$extension->setConfig($this->loadConfig());
		$builder = new ContainerBuilder();
		$builder->parameters['wwwDir'] = '/www';
		$builder->parameters['productionMode'] = false;
		$extension->setCompiler(new Compiler($builder), 'webpack');
		return $extension;
	}

	/**
	 * @return mixed[]
	 */
	private function loadConfig(): array
	{
		$file = Bootstrap::rootDir()->string('examples', 'config', 'common.neon');
		$neon = Neon::decode(FileSystem::read($file));
		return $neon['webpack'];
	}

}
