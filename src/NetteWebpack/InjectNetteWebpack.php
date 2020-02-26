<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectNetteWebpack
{

	protected NetteWebpack $netteWebpack;

	public function injectNetteWebpack(NetteWebpack $netteWebpack): void
	{
		$this->netteWebpack = $netteWebpack;
	}

}
