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
            'object' => '\ArrayObject',
            'fields' => array(
                'name' => 'string',
                'value' => 'string'
            )
        );
    }

    /**
     * @param $name
     * @return mixed
     */
    public function fetch($name)
    {
        $rowset = $this->select(array(
            'name' => $name
        ));

        $row = $rowset->current();
        if ($row != null) {
            $setting = $row['value'];
        }

        return $setting;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $rowset = $this->select(array(
            'name' => $name
        ));

        $row = $rowset->current();
        if ($row == null) {
            $this->insert(array(
                'name' => $name,
                'value' => $value
            ));
        } else {
            $this->update(array(
                    'value' => $value
                ),
                array(
                    'id' => $row['id']
                )
            );
        }
    }
}