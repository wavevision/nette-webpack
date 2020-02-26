<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\Debugger;

use Latte\Engine;
use Tracy\IBarPanel;
use Wavevision\NetteWebpack\NetteWebpackParameters;

class WebpackPanel implements IBarPanel
{

	public const DEBUGGER = 'debugger';

	private const ASSETS_COUNT = 'assetsCount';

	private const ENTRIES_COUNT = 'entriesCount';

	private Engine $latte;

	private NetteWebpackParameters $webpackParameters;

	public function __construct(NetteWebpackParameters $webpackParameters)
	{
		$this->latte = new Engine();
		$this->webpackParameters = $webpackParameters;
	}

	public function getTab(): string
	{
		return $this->latte->renderToString(
			$this->getTemplate('tab'),
			[
				self::ASSETS_COUNT => $this->getAssetsCount(),
				self::ENTRIES_COUNT => $this->getEntriesCount(),
				NetteWebpackParameters::DEV_SERVER => $this->webpackParameters->getDevServer()->getEnabled(),
			]
		);
	}

	public function getPanel(): string
	{
		return $this->latte->renderToString(
			$this->getTemplate('panel'),
			[
				'assets' => $this->webpackParameters->getResolvedAssetsWithoutEntryChunks(),
				'basePath' => $this->webpackParameters->getUrl(),
				self::ASSETS_COUNT => $this->getAssetsCount(),
				self::ENTRIES_COUNT => $this->getEntriesCount(),
				NetteWebpackParameters::CHUNKS => $this->webpackParameters->getResolvedEntryChunks(),
				NetteWebpackParameters::DEV_SERVER => $this->webpackParameters->getDevServer()->getEnabled(),
			]
		);
	}

	private function getAssetsCount(): int
	{
		return count($this->webpackParameters->getResolvedAssets());
	}

	private function getEntriesCount(): int
	{
		return count(array_keys($this->webpackParameters->getResolvedEntryChunks()));
	}

	private function getTemplate(string $template): string
	{
		return __DIR__ . "/templates/$template.latte";
	}

}
