<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\SmartObject;

final class EntryChunks
{

	use SmartObject;

	/**
	 * @var string[]
	 */
	private array $scripts;

	/**
	 * @var string[]
	 */
	private array $styles;

	/**
	 * @param string [] $scripts
	 * @param string [] $styles
	 */
	public function __construct(array $scripts, array $styles)
	{
		$this->scripts = $scripts;
		$this->styles = $styles;
	}

	/**
	 * @return string[]
	 */
	public function getScripts(): array
	{
		return $this->scripts;
	}

	/**
	 * @return string[]
	 */
	public function getStyles(): array
	{
		return $this->styles;
	}

}
