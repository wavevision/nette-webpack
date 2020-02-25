<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackExamples\Presenters;

use Nette\Application\UI\Presenter;
use Wavevision\NetteWebpack\UI\Components\Assets\AssetsComponent;

abstract class BasePresenter extends Presenter
{

	use AssetsComponent;

}
