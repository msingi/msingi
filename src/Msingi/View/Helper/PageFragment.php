<?php

namespace Msingi\View\Helper;

class PageFragment extends \Zend\View\Helper\AbstractHelper
{
    public function __invoke($name)
    {
        return 'page fragment: ' . $name;
    }
}