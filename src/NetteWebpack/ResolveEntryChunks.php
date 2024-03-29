<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\SmartObject;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\Utils\Arrays;
use Wavevision\Utils\ContentTypes;
use function array_filter;
use function array_merge;
use function array_unique;
use function in_array;

/**
 * @DIService(generateInject=true)
 */
class ResolveEntryChunks
{

	use SmartObject;
	use InjectNetteWebpack;

	/**
	 * @param string[] ...$chunks
	 * @return string[]
	 */
	public function merge(array ...$chunks): array
	{
		return array_unique(array_merge(...$chunks));
	}

	public function process(string ...$entries): EntryChunks
	{
		return new EntryChunks(
			$this->merge(...Arrays::map($entries, [$this, 'resolveScripts'])),
			$this->merge(...Arrays::map($entries, [$this, 'resolveStyles']))
		);
	}

	/**
	 * @return string[]
	 */
	public function resolveAll(string $entry): array
	{
		$entries = $this->netteWebpack->getEnabledEntries();
		if (!in_array($entry, $entries)) {
			throw new InvalidState("Invalid webpack entry '$entry'.");
		}
		return $this->netteWebpack->getEntryChunks($entry);
	}

	/**
	 * @return string[]
	 */
	public function resolveScripts(string $entry): array
	{
		return $this->filterChunks($entry, ContentTypes::JS);
	}

	/**
	 * @return string[]
	 */
	public function resolveStyles(string $entry): array
	{
		return $this->filterChunks($entry, ContentTypes::CSS);
	}

	/**
	 * @return string[]
	 */
	private function filterChunks(string $entry, string $contentType): array
	{
		return array_filter(
			$this->resolveAll($entry),
			function (string $chunk) use ($contentType): bool {
				return FormatChunkName::isFormatted($chunk, $contentType);
			}
		);
	}

}
