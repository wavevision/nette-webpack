<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectNetteWebpackParameters
{

	protected NetteWebpackParameters $webpackParameters;

	public function injectWebpackParameters(NetteWebpackParameters $webpackParameters): void
	{
		$this->webpackParameters = $webpackParameters;
	}

}
