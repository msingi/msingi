<?php

namespace Msingi\View\Helper;

class PageFragment extends \Zend\View\Helper\AbstractHelper
{
    /**
     * @param $name
     * @return string
     */
    public function __invoke($name)
    {
        return 'page fragment: ' . $name;
    }
}