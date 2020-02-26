<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectNetteWebpackParameters
{

	protected NetteWebpackParameters $netteWebpackParameters;

	public function injectWebpackParameters(NetteWebpackParameters $netteWebpackParameters): void
	{
		$this->netteWebpackParameters = $netteWebpackParameters;
	}

}
