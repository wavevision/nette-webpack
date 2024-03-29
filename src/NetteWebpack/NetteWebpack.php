<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\SmartObject;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\Utils\Path;
use function array_filter;
use function array_keys;
use function sprintf;

class NetteWebpack
{

	use SmartObject;
	use InjectLoadManifest;
	use InjectRequest;

	public const CHUNKS = 'chunks';

	public const DEV_SERVER = 'devServer';

	public const DIR = 'dir';

	public const DIST = 'dist';

	public const ENTRIES = 'entries';

	public const MANIFEST = 'manifest';

	/**
	 * @var array<string, string>
	 */
	private array $assets;

	/**
	 * @var array<string, array<string>>
	 */
	private array $chunks;

	private DevServer $devServer;

	private string $dir;

	private string $dist;

	/**
	 * @var array<string, bool>
	 */
	private array $entries;

	private bool $productionMode;

	/**
	 * @var array<string, string>|null
	 */
	private ?array $manifest;

	/**
	 * @param array<string, bool> $entries
	 * @param array<string, string>|null $manifest
	 */
	public function __construct(
		DevServer $devServer,
		string $dir,
		string $dist,
		array $entries,
		bool $productionMode,
		?array $manifest = null
	) {
		$this->assets = [];
		$this->chunks = [];
		$this->devServer = $devServer;
		$this->dir = $dir;
		$this->dist = $dist;
		$this->entries = $entries;
		$this->productionMode = $productionMode;
		$this->manifest = $manifest;
	}

	public function getAsset(string $name): string
	{
		if (!isset($this->assets[$name])) {
			/** @var string|null $asset */
			$asset = $this->getManifest()[$name] ?? null;
			if (!$asset) {
				throw new InvalidState("Webpack asset '$name' does not exist.");
			}
			$this->assets[$name] = $asset;
		}
		return $this->assets[$name];
	}

	public function getBasePath(): string
	{
		if ($this->getDevServer()->getEnabled()) {
			return $this->getDevServer()->getUrl();
		}
		return $this->request->getUrl()->getBasePath();
	}

	/**
	 * @return array<array<string>>
	 */
	public function getChunks(): array
	{
		$chunks = $this->getManifest()[self::CHUNKS] ?? null;
		if (!$chunks) {
			throw new InvalidState(sprintf("Invalid webpack manifest format, property '%s' is missing.", self::CHUNKS));
		}
		return $chunks;
	}

	public function getDevServer(): DevServer
	{
		return $this->devServer;
	}

	public function getDir(): string
	{
		return $this->dir;
	}

	public function getDist(): string
	{
		return $this->dist;
	}

	/**
	 * @return array<string, bool>
	 */
	public function getEntries(): array
	{
		return $this->entries;
	}

	/**
	 * @return string[]
	 */
	public function getEnabledEntries(): array
	{
		return $this->getEnabledRecords($this->getEntries());
	}

	/**
	 * @return string[]
	 */
	public function getEntryChunks(string $entry): array
	{
		if (!isset($this->chunks[$entry])) {
			$chunks = $this->getChunks()[$entry] ?? null;
			if (!$chunks) {
				throw new InvalidState("Webpack chunks for entry '$entry' do not exist.");
			}
			$this->chunks[$entry] = $chunks;
		}
		return $this->chunks[$entry];
	}

	public function getUrl(string ...$path): string
	{
		return Path::join($this->getBasePath(), $this->getDist(), ...$path);
	}

	/**
	 * @return mixed[]
	 */
	public function getManifest(): array
	{
		if (!$this->manifest) {
			$this->manifest = $this->loadManifest->process();
		}
		return $this->manifest;
	}

	/**
	 * @return array<string, string>
	 */
	public function getResolvedAssets(): array
	{
		return $this->assets;
	}

	/**
	 * @return array<string, string>
	 */
	public function getResolvedAssetsWithoutEntryChunks(): array
	{
		$assets = $this->getResolvedAssets();
		$entryChunks = $this->getResolvedEntryChunks();
		foreach ($entryChunks as $chunks) {
			foreach (array_keys($chunks) as $chunk) {
				unset($assets[$chunk]);
			}
		}
		return $assets;
	}

	/**
	 * @return array<string, array<string>>
	 */
	public function getResolvedEntryChunks(): array
	{
		$entryChunks = [];
		foreach ($this->chunks as $entry => $chunks) {
			if (!isset($entryChunks[$entry])) {
				$entryChunks[$entry] = [];
			}
			foreach ($chunks as $chunk) {
				$entryChunks[$entry][$chunk] = $this->getAsset($chunk);
			}
		}
		return $entryChunks;
	}

	public function isProduction(): bool
	{
		return $this->productionMode;
	}

	/**
	 * @param array<string, bool> $records
	 * @return string[]
	 */
	private function getEnabledRecords(array $records): array
	{
		return array_keys(array_filter($records, fn(bool $enabled): bool => $enabled));
	}

}
