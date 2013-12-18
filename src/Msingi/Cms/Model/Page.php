<?php

namespace Msingi\Cms\Model;

use Msingi\Db\TableRow;

class Page extends TableRow
{
    protected static function getDefinition()
    {
        return array(
            'type' => 'string',
            'path' => 'string',
            'template' => 'string'
        );
    }
}