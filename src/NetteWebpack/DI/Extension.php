<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\DI;

use Nette\DI\CompilerExtension;
use Nette\Http\IRequest;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Wavevision\NetteWebpack\DevServer;
use Wavevision\NetteWebpack\LoadManifest;
use Wavevision\NetteWebpack\WebpackParameters;
use Wavevision\Utils\Path;
use Wavevision\Utils\Server;

class Extension extends CompilerExtension
{

	private const DEFAULT_URL = 'http://localhost:9006';

	private const DEFAULT_MANIFEST = 'manifest.json';

	private bool $consoleMode;

	private bool $debugMode;

	public function __construct(bool $debugMode, ?bool $consoleMode = null)
	{
		$this->consoleMode = $consoleMode !== null ? $consoleMode : Server::isCLI();
		$this->debugMode = $debugMode;
	}

	public function getConfigSchema(): Schema
	{
		return Expect::structure(
			[
				WebpackParameters::DEV_SERVER => Expect::structure(
					[
						DevServer::ENABLED => Expect::bool($this->debugMode),
						DevServer::URL => Expect::string(self::DEFAULT_URL),
					]
				),
				WebpackParameters::DIR => Expect::string(
					Path::join($this->getParameter('wwwDir'), WebpackParameters::DIST)
				),
				WebpackParameters::DIST => Expect::string(WebpackParameters::DIST),
				WebpackParameters::ENTRIES => Expect::arrayOf(Expect::bool())->default([]),
				WebpackParameters::MANIFEST => Expect::string(self::DEFAULT_MANIFEST),
			]
		);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = (array)$this->getConfig();
		$devServer = new DevServer($config[WebpackParameters::DEV_SERVER]);
		$serviceSetup = new ServiceSetup($devServer, $config);
		$webpackParameters = $builder->addDefinition($this->prefix('webpackParameters'));
		$webpackParametersSetup = $serviceSetup->get($config[WebpackParameters::ENTRIES], $this->isProduction());
		$loadManifestSetup = $serviceSetup->get($config[WebpackParameters::MANIFEST], $this->consoleMode);
		if ($this->isProduction()) {
			$loadManifest = new LoadManifest(...$loadManifestSetup);
			$webpackParametersSetup[] = $this->consoleMode ? null : $loadManifest->process();
			$webpackParameters
				->setFactory(WebpackParameters::class, $webpackParametersSetup)
				->addSetup('injectLoadManifest', [$loadManifest]);
			$this->compiler->addDependencies([$loadManifest->getManifestPath()]);
		} else {
			$loadManifest = $builder
				->addDefinition($this->prefix('loadManifest'))
				->setFactory(LoadManifest::class, $loadManifestSetup)
				->setAutowired(false);
			$webpackParameters
				->setFactory(WebpackParameters::class, $webpackParametersSetup)
				->addSetup('injectLoadManifest', [$loadManifest]);
		}
		$this->loadDefinitionsFromConfig($this->getServices());
	}

	public function afterCompile(ClassType $class): void
	{
		$initialize = $class->getMethod('initialize');
		$initialize->addBody(
			'$this->getByType(?)->injectRequest($this->getByType(?));',
			[WebpackParameters::class, IRequest::class]
		);
	}

	/**
	 * @return mixed
	 */
	private function getParameter(string $parameter)
	{
		return $this->getContainerBuilder()->parameters[$parameter];
	}

	/**
	 * @return mixed[]
	 */
	private function getServices(): array
	{
		$services = 'services';
		$path = Path::create(__DIR__, '..', '..', '..', 'config', "$services.neon");
		return $this->loadFromFile((string)$path)[$services];
	}

	private function isProduction(): bool
	{
		return $this->getParameter('productionMode');
	}

}
