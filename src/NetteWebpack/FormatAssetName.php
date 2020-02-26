<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\SmartObject;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\Utils\Path;

/**
 * @DIService(generateInject=true)
 */
class FormatAssetName
{

	use SmartObject;
	use InjectWebpackParameters;

	public function process(string ...$path): string
	{
		return $this->webpackParameters->getUrl($this->webpackParameters->getAsset(Path::join(...$path)));
	}

}
