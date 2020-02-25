<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests;

use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\NetteWebpack\ResolveEntryChunks;

class ResolveEntryChunksTest extends DIContainerTestCase
{

	public function testResolveAll(): void
	{
		/** @var ResolveEntryChunks $resolveEntryChunks */
		$resolveEntryChunks = $this->getContainer()->getByType(ResolveEntryChunks::class);
		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage("Invalid webpack entry 'test'.");
		$resolveEntryChunks->resolveAll('test');
	}

}
