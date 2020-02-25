<?php declare (strict_types = 1);

namespace Wavevision\NetteWebpackTests\UI\Components\Assets;

use Wavevision\NetteTests\Runners\PresenterRequest;
use Wavevision\NetteWebpack\Exceptions\InvalidState;
use Wavevision\NetteWebpackExamples\Presenters\ExamplesPresenter;
use Wavevision\NetteWebpackTests\PresenterTestCase;

class AssetsTest extends PresenterTestCase
{

	public function testDefault(): void
	{
		$this->assertStringContainsString(
			'Hello there!',
			$this->extractTextResponseContent(
				$this->runPresenter(new PresenterRequest(ExamplesPresenter::class, ExamplesPresenter::DEFAULT_ACTION))
			)
		);
	}

	public function testError(): void
	{
		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage('Invalid use of assets component. Use assets:scripts and assets:styles.');
		$this->runPresenter(new PresenterRequest(ExamplesPresenter::class, 'error'));
	}

}
