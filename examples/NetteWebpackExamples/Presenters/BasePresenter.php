<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackExamples\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Wavevision\NetteWebpack\UI\Components\Assets\AssetsComponent;

/**
 * @property-read DefaultTemplate $template
 */
abstract class BasePresenter extends Presenter
{

	use AssetsComponent;

}
