<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackExamples;

use Nette\Configurator;
use Nette\StaticClass;
use Wavevision\Utils\Path;

class Bootstrap
{

	use StaticClass;

	public static function boot(): Configurator
	{
		return self::createConfigurator();
	}

	public static function createConfigurator(): Configurator
	{
		$configurator = new Configurator();
		$rootDir = self::rootDir();
		$configurator
			->setTimeZone('Europe/Prague')
			->setTempDirectory($rootDir->string('temp'))
			->addConfig($rootDir->string('examples', 'config', 'common.neon'));
		return $configurator;
	}

	public static function rootDir(): Path
	{
		return Path::create(__DIR__, '..', '..');
	}

}
