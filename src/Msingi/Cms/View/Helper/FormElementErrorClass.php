<?php

namespace Msingi\Cms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormElementErrorClass extends AbstractHelper
{
    /**
     * @param $e
     * @param $c
     * @return string
     */
    public function __invoke($e, $c)
    {
        return (count($e->getMessages()) > 0) ? $c : '';
    }
}