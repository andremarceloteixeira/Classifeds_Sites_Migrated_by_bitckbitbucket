<?php

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Components\Translate;


class TranslateHelper extends AbstractHelper
{

    public function __invoke ($text = "", $params = array())
    {
        return Translate::t($text, $params);
    }
}

