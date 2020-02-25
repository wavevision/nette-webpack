<?php declare (strict_types = 1);

use Nette\Configurator;
use Wavevision\NetteTests\Configuration;
use Wavevision\NetteWebpackExamples\Bootstrap;

require __DIR__ . '/../vendor/autoload.php';
Configuration::setup(
	function (): Configurator {
		$configurator = Bootstrap::createConfigurator();
		$configurator->addParameters(['wwwDir' => Bootstrap::rootDir()->string('examples', 'www')]);
		return $configurator;
	}
);
