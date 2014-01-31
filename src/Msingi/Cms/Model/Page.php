<?php

namespace Msingi\Cms\Model;

use Msingi\Db\TableRow;

/**
 * Class Page
 * @package Msingi\Cms\Model
 */
class Page extends TableRow
{
    const TYPE_MVC = 'mvc';
    const TYPE_STATIC = 'static';

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return isset($this->data['children']) && count($this->data['children']) > 0;
    }
}