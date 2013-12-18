<?php

namespace Msingi\Cms\Model;

use Msingi\Db\TableRow;

class Menu extends TableRow
{
    protected static function getDefinition()
    {
        return array(
            'route' => 'string',
            'params' => 'string',
            'label' => 'string'
        );
    }
}