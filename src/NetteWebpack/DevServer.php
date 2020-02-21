<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\SmartObject;

final class DevServer
{

	use SmartObject;

	public const ENABLED = 'enabled';

	public const URL = 'url';

	private bool $enabled;

	private string $url;

	/**
	 * @param mixed[] $devServer
	 */
	public function __construct(array $devServer)
	{
		$this->enabled = $devServer[self::ENABLED];
		$this->url = $devServer[self::URL];
	}

	public function getEnabled(): bool
	{
		return $this->enabled;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

}
