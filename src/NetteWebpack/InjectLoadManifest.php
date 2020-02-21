<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectLoadManifest
{

	protected LoadManifest $loadManifest;

	public function injectLoadManifest(LoadManifest $loadManifest): void
	{
		$this->loadManifest = $loadManifest;
	}

}
