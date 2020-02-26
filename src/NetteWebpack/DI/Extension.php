<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Nette\Http\IRequest;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Tracy\IBarPanel;
use Wavevision\NetteWebpack\Debugger\WebpackPanel;
use Wavevision\NetteWebpack\DevServer;
use Wavevision\NetteWebpack\LoadManifest;
use Wavevision\NetteWebpack\NetteWebpack;
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

	/**
	 * @return mixed[]
	 */
	public function getConfig(): array
	{
		return (array)parent::getConfig();
	}

	public function getConfigSchema(): Schema
	{
		return Expect::structure(
			[
				WebpackPanel::DEBUGGER => Expect::bool($this->debugMode),
				NetteWebpack::DEV_SERVER => Expect::structure(
					[
						DevServer::ENABLED => Expect::bool($this->debugMode),
						DevServer::URL => Expect::string(self::DEFAULT_URL),
					]
				)->castTo('array'),
				NetteWebpack::DIR => Expect::string(
					Path::join($this->getParameter('wwwDir'), NetteWebpack::DIST)
				),
				NetteWebpack::DIST => Expect::string(NetteWebpack::DIST),
				NetteWebpack::ENTRIES => Expect::arrayOf(Expect::bool())->default([]),
				NetteWebpack::MANIFEST => Expect::string(self::DEFAULT_MANIFEST),
			]
		)->castTo('array');
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();
		$devServer = new DevServer($config[NetteWebpack::DEV_SERVER]);
		$serviceSetup = new ServiceSetup($devServer, $config);
		$webpackParameters = $builder->addDefinition($this->prefix('webpackParameters'));
		$webpackParametersSetup = $serviceSetup->get($config[NetteWebpack::ENTRIES], $this->isProduction());
		$loadManifestSetup = $serviceSetup->get($config[NetteWebpack::MANIFEST], $this->consoleMode);
		if ($this->isProduction()) {
			$loadManifest = new LoadManifest(...$loadManifestSetup);
			$webpackParametersSetup[] = $this->consoleMode ? null : $loadManifest->process();
			$webpackParameters
				->setFactory(NetteWebpack::class, $webpackParametersSetup)
				->addSetup('injectLoadManifest', [$loadManifest]);
			$this->compiler->addDependencies([$loadManifest->getManifestPath()]);
		} else {
			$loadManifest = $builder
				->addDefinition($this->prefix('loadManifest'))
				->setFactory(LoadManifest::class, $loadManifestSetup)
				->setAutowired(false);
			$webpackParameters
				->setFactory(NetteWebpack::class, $webpackParametersSetup)
				->addSetup('injectLoadManifest', [$loadManifest]);
		}
		$this->loadDefinitionsFromConfig($this->getServices());
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();
		if ($config[WebpackPanel::DEBUGGER] && interface_exists(IBarPanel::class)) {
			$definition = $builder->getDefinition($this->prefix('webpackParameters'));
			if ($definition instanceof ServiceDefinition) {
				$definition->addSetup('@Tracy\Bar::addPanel', [new Statement(WebpackPanel::class)]);
			}
		}
	}

	public function afterCompile(ClassType $class): void
	{
		$initialize = $class->getMethod('initialize');
		$initialize->addBody(
			'$this->getByType(?)->injectRequest($this->getByType(?));',
			[NetteWebpack::class, IRequest::class]
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
