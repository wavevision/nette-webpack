<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackExamples\Presenters;

use Wavevision\NetteWebpack\InjectFormatAssetName;
use Wavevision\NetteWebpack\InjectResolveEntryChunks;

final class ExamplesPresenter extends BasePresenter
{

	use InjectFormatAssetName;
	use InjectResolveEntryChunks;

	public function actionDefault(): void
	{
		$this->getAssetsComponent()->setChunks($this->resolveEntryChunks->process('entry'));
		$this->template->setParameters(
			[
				'image' => $this->formatAssetName->process('images', 'image.png'),
			]
		);
	}

	public function actionAdd(): void
	{
		$this->getAssetsComponent()->addChunks($this->resolveEntryChunks->process('entry'));
	}

	public function actionError(): void
	{
		$this->getAssetsComponent()->render();
	}

}
