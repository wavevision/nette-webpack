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

class Extension extends CompilerExtension
{

	private const DEFAULT_URL = 'http://localhost:9006';

	private const DEFAULT_MANIFEST = 'manifest.json';

	public function getConfigSchema(): Schema
	{
		return Expect::structure(
			[
				WebpackParameters::DEV_SERVER => Expect::structure(
					[
						DevServer::ENABLED => Expect::bool($this->getParameter('debugMode')),
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
		$productionMode = $this->getParameter('productionMode');
		$webpackParameters = $builder->addDefinition($this->prefix('webpackParameters'));
		$webpackParametersSetup = $this->getWebpackParametersSetup($devServer, $config, $productionMode);
		$loadManifestSetup = $this->getLoadManifestSetup($devServer, $config);
		if ($productionMode) {
			$loadManifest = new LoadManifest(...$loadManifestSetup);
			$webpackParametersSetup[] = $loadManifest->process();
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
	 * @param mixed[] $config
	 * @return mixed[]
	 */
	private function getLoadManifestSetup(DevServer $devServer, array $config): array
	{
		return [
			$devServer,
			$config[WebpackParameters::DIR],
			$config[WebpackParameters::DIST],
			$config[WebpackParameters::MANIFEST],
		];
	}

	/**
	 * @return mixed
	 */
	private function getParameter(string $parameter)
	{
		return $this->getContainerBuilder()->parameters[$parameter] ?? null;
	}

	/**
	 * @return mixed[]
	 */
	private function getServices(): array
	{
		return $this->loadFromFile(Path::join(__DIR__, '..', '..', '..', 'config', 'services.neon'))['services'];
	}

	/**
	 * @param mixed[] $config
	 * @return mixed[]
	 */
	private function getWebpackParametersSetup(DevServer $devServer, array $config, bool $productionMode): array
	{
		return [
			$devServer,
			$config[WebpackParameters::DIR],
			$config[WebpackParameters::DIST],
			$config[WebpackParameters::ENTRIES],
			$productionMode,
		];
	}

}
