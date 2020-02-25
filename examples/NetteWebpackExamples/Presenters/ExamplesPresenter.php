<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackExamples\Presenters;

use Wavevision\NetteWebpack\InjectResolveEntryChunks;

final class ExamplesPresenter extends BasePresenter
{

	use InjectResolveEntryChunks;

	public function actionDefault(): void
	{
		$this->getAssetsComponent()->setChunks($this->resolveEntryChunks->process('entry'));
	}

	public function actionError(): void
	{
		$this->getAssetsComponent()->render();
	}

}
