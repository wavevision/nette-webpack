<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\Debugger;

use Latte\Engine;
use Tracy\IBarPanel;
use Wavevision\NetteWebpack\NetteWebpack;

class WebpackPanel implements IBarPanel
{

	public const DEBUGGER = 'debugger';

	private const ASSETS_COUNT = 'assetsCount';

	private const ENTRIES_COUNT = 'entriesCount';

	private Engine $latte;

	private NetteWebpack $netteWebpack;

	public function __construct(NetteWebpack $netteWebpack)
	{
		$this->latte = new Engine();
		$this->netteWebpack = $netteWebpack;
	}

	public function getTab(): string
	{
		return $this->latte->renderToString(
			$this->getTemplate('tab'),
			[
				self::ASSETS_COUNT => $this->getAssetsCount(),
				self::ENTRIES_COUNT => $this->getEntriesCount(),
				NetteWebpack::DEV_SERVER => $this->netteWebpack->getDevServer()->getEnabled(),
			]
		);
	}

	public function getPanel(): string
	{
		return $this->latte->renderToString(
			$this->getTemplate('panel'),
			[
				'assets' => $this->netteWebpack->getResolvedAssetsWithoutEntryChunks(),
				'basePath' => $this->netteWebpack->getUrl(),
				self::ASSETS_COUNT => $this->getAssetsCount(),
				self::ENTRIES_COUNT => $this->getEntriesCount(),
				NetteWebpack::CHUNKS => $this->netteWebpack->getResolvedEntryChunks(),
				NetteWebpack::DEV_SERVER => $this->netteWebpack->getDevServer()->getEnabled(),
			]
		);
	}

	private function getAssetsCount(): int
	{
		return count($this->netteWebpack->getResolvedAssets());
	}

	private function getEntriesCount(): int
	{
		return count(array_keys($this->netteWebpack->getResolvedEntryChunks()));
	}

	private function getTemplate(string $template): string
	{
		return __DIR__ . "/templates/$template.latte";
	}

}
