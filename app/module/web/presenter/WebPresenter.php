<?php

declare(strict_types = 1);

namespace Module\Web;

use App\Base;
use App\Front;
use Nette\Application\UI\Presenter;


final class WebPresenter extends Presenter
{
	use Base;
	use Front;
}
