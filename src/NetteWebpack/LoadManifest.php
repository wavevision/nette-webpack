<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\SmartObject;
use Nette\Utils\Json;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\Utils\Path;

/**
 * @internal
 */
class LoadManifest
{

	use SmartObject;

	private string $dir;

	private string $dist;

	private DevServer $devServer;

	private string $manifest;

	public function __construct(DevServer $devServer, string $dir, string $dist, string $manifest)
	{
		$this->devServer = $devServer;
		$this->dir = $dir;
		$this->dist = $dist;
		$this->manifest = $manifest;
	}

	/**
	 * @return mixed[]
	 */
	public function process(): array
	{
		$context = stream_context_create(['ssl' => ['verify_peer' => false]]);
		$manifest = @file_get_contents($this->getManifestPath(), false, $context);
		if ($manifest === false) {
			throw new InvalidState('Unable to load webpack manifest file.');
		}
		return Json::decode($manifest, Json::FORCE_ARRAY);
	}

	public function getManifestPath(): string
	{
		if ($this->devServer->getEnabled()) {
			return Path::join($this->devServer->getUrl(), $this->dist, $this->manifest);
		}
		return Path::join($this->dir, $this->manifest);
	}

}
