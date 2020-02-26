<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectFormatAssetName
{

	protected FormatAssetName $formatAssetName;

	public function injectFormatAssetName(FormatAssetName $formatAssetName): void
	{
		$this->formatAssetName = $formatAssetName;
	}

}
