<?php

namespace Msingi\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormElementErrorClass extends AbstractHelper
{
    public function __invoke($e, $c)
    {
        return (count($e->getMessages()) > 0) ? $c : '';
    }
}