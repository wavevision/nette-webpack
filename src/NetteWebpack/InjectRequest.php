<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpack;

use Nette\Http\Request;

trait InjectRequest
{

	protected Request $request;

	public function injectRequest(Request $request): void
	{
		$this->request = $request;
	}

}
