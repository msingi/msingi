<?php

namespace Msingi\Cms\Model;

use Msingi\Db\TableRow;

class PageFragment extends TableRow
{
    protected static function getDefinition()
    {
        return array(
            'content' => 'string'
        );
    }
}