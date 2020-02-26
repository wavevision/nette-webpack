<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\DI;

use Nette\SmartObject;
use Wavevision\NetteWebpack\DevServer;
use Wavevision\NetteWebpack\NetteWebpackParameters;

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
		return array_merge(
			[
				$this->devServer,
				$this->config[NetteWebpackParameters::DIR],
				$this->config[NetteWebpackParameters::DIST],
			],
			$parameters
		);
	}

}
