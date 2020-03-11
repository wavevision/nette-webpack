<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\UI\Components\Assets;

use Wavevision\NetteWebpack\InjectFormatChunkName;
use Wavevision\NetteWebpack\UI\BaseControl;
use Wavevision\Utils\Arrays;

abstract class Chunks extends BaseControl
{

	use InjectFormatChunkName;

	/**
	 * @var string[]
	 */
	private array $chunks = [];

	public function render(): void
	{
		$this->template
			->setParameters(
				[
					'chunks' => Arrays::map($this->chunks, [$this, 'formatChunkName']),
					'contentType' => $this->getContentType(),
				]
			)
			->render();
	}

	public function addChunk(string $chunk): self
	{
		$this->chunks[] = $chunk;
		return $this;
	}

	/**
	 * @param string[] $chunks
	 */
	public function setChunks(array $chunks): self
	{
		$this->chunks = $chunks;
		return $this;
	}

	abstract public function formatChunkName(string $chunk): string;

	abstract public function getContentType(): string;

}
