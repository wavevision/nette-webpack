<?php declare (strict_types = 1);

use Nette\Configurator;
use Wavevision\NetteTests\Configuration;
use Wavevision\Utils\Path;

require __DIR__ . '/../vendor/autoload.php';
Configuration::setup(
	function (): Configurator {
		$configurator = new Configurator();
		$rootDir = Path::create(__DIR__ . '..');
		$configurator->addConfig($rootDir->string('tests', 'config', 'common.neon'));
		$tempDir = $rootDir->path('temp');
		$configurator->setTempDirectory((string)$tempDir);
		$configurator->addParameters(['wwwDir' => $tempDir->string('www')]);
		return $configurator;
	}
);
