<?php

namespace Msingi\Cms\Model;

use Msingi\Db\TableRow;

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