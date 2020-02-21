<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectFormatChunkName
{

	protected FormatChunkName $formatChunkName;

	public function injectFormatChunkName(FormatChunkName $formatChunkName): void
	{
		$this->formatChunkName = $formatChunkName;
	}

}
