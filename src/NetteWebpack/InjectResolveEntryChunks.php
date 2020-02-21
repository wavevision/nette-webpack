<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectResolveEntryChunks
{

	protected ResolveEntryChunks $resolveEntryChunks;

	public function injectResolveEntryChunks(ResolveEntryChunks $resolveEntryChunks): void
	{
		$this->resolveEntryChunks = $resolveEntryChunks;
	}

}
