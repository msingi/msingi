<?php

namespace Msingi\Cms\Model;

use Msingi\Db\TableRow;

/**
 * Class Page
 * @package Msingi\Cms\Model
 */
class Page extends TableRow
{
    /**
     * @return bool
     */
    public function hasChildren()
    {
        return isset($this->data['children']) && count($this->data['children']) > 0;
    }
}