<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\SmartObject;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\Utils\ContentTypes;
use Wavevision\Utils\Strings;

/**
 * @DIService(generateInject=true)
 */
class FormatChunkName
{

	use SmartObject;
	use InjectWebpackParameters;

	public function formatScript(string $name): string
	{
		return $this->process($name, ContentTypes::JS);
	}

	public function formatStyle(string $name): string
	{
		return $this->process($name, ContentTypes::CSS);
	}

	public function process(string $name, string $contentType): string
	{
		if (!self::isFormatted($name, $contentType)) {
			$name = ContentTypes::getFilename($name, $contentType);
		}
		return $this->webpackParameters->getUrl($this->webpackParameters->getAsset($name));
	}

	public static function isFormatted(string $name, string $contentType): bool
	{
		return Strings::endsWith($name, ContentTypes::getExtension($contentType, true));
	}

}
