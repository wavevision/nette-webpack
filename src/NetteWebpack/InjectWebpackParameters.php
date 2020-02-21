<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

trait InjectWebpackParameters
{

	protected WebpackParameters $webpackParameters;

	public function injectWebpackParameters(WebpackParameters $webpackParameters): void
	{
		$this->webpackParameters = $webpackParameters;
	}

}
