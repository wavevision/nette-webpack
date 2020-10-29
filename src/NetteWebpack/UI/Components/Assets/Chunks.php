<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\UI\Components\Assets;

use Wavevision\NetteWebpack\InjectFormatChunkName;
use Wavevision\NetteWebpack\UI\BaseControl;
use Wavevision\Utils\Arrays;
use function in_array;

abstract class Chunks extends BaseControl
{

	use InjectFormatChunkName;

	/**
	 * @var string[]
	 */
	private array $chunks = [];

	abstract public function formatChunkName(string $chunk): string;

	abstract public function getContentType(): string;

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
		if (!in_array($chunk, $this->chunks)) {
			$this->chunks[] = $chunk;
		}
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

}
