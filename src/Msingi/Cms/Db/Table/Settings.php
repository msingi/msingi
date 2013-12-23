<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\Table;
use Zend\Db\Sql\Select;

class Settings extends Table
{
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_settings',
            'fields' => array(
                'name' => 'string',
                'value' => 'string'
            )
        );
    }


}