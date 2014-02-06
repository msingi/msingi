<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\Table;
use Zend\Db\Sql\Select;

class PageTemplates extends Table
{
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_page_templates',
            'object' => '\ArrayObject',
            'fields' => array(
                'name' => 'string',
                'fragments' => 'string'
            )
        );
    }

    /**
     * Fetch page template by name
     *
     * @param $name
     * @return array|\ArrayObject|null
     */
    public function fetchByName($name)
    {
        $resultSet = $this->select(array('name' => $name));

        return $resultSet->current();
    }

    /**
     * Fetch templates list as associative array
     *
     * @return array
     */
    public function fetchOptions()
    {
        $resultSet = $this->select();
        $result = array();
        foreach ($resultSet as $row) {
            $result[$row['name']] = $row['label'];
        }
        return $result;
    }
}