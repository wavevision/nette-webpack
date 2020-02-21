<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack\UI\Components\Assets\Styles;

use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\NetteWebpack\UI\Components\Assets\Chunks;
use Wavevision\Utils\ContentTypes;

/**
 * @DIService(generateComponent=true)
 */
final class Control extends Chunks
{

	public function formatChunkName(string $chunk): string
	{
		return $this->formatChunkName->formatStyle($chunk);
	}

	public function getContentType(): string
	{
		return ContentTypes::CSS;
	}

}
