<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\UI\Components\Assets;

use Wavevision\NetteWebpack\EntryChunks;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\NetteWebpack\UI\BaseControl;
use Wavevision\NetteWebpack\UI\Components\Assets\Scripts\ScriptsComponent;
use Wavevision\NetteWebpack\UI\Components\Assets\Styles\StylesComponent;

final class Control extends BaseControl
{

	use ScriptsComponent;
	use StylesComponent;

	public function render(): void
	{
		throw new InvalidState('Invalid use of assets component. Use assets:scripts and assets:styles.');
	}

	public function renderScripts(): void
	{
		$this->template->render($this->getTemplateFile('scripts'));
	}

	public function renderStyles(): void
	{
		$this->template->render($this->getTemplateFile('styles'));
	}

	public function setChunks(EntryChunks $chunks): self
	{
		return $this
			->setScripts($chunks->getScripts())
			->setStyles($chunks->getStyles());
	}

	/**
	 * @param string[] $scripts
	 */
	public function setScripts(array $scripts): self
	{
		$this->getScriptsComponent()->setChunks($scripts);
		return $this;
	}

	/**
	 * @param string[] $styles
	 */
	public function setStyles(array $styles): self
	{
		$this->getStylesComponent()->setChunks($styles);
		return $this;
	}

}
