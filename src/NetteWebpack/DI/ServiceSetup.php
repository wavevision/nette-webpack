<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\DI;

use Nette\SmartObject;
use Wavevision\NetteWebpack\DevServer;
use Wavevision\NetteWebpack\WebpackParameters;
use Wavevision\Utils\Arrays;

/**
 * @internal
 */
final class ServiceSetup
{

	use SmartObject;

	private DevServer $devServer;

	/**
	 * @var mixed[] $config
	 */
	private array $config;

	/**
	 * @param mixed[] $config
	 */
	public function __construct(DevServer $devServer, array $config)
	{
		$this->devServer = $devServer;
		$this->config = $config;
	}

	/**
	 * @param mixed ...$parameters
	 * @return mixed[]
	 */
	public function get(...$parameters): array
	{
		return Arrays::mergeAllRecursive(
			[
				$this->devServer,
				$this->config[WebpackParameters::DIR],
				$this->config[WebpackParameters::DIST],
			],
			$parameters
		);
	}

}
