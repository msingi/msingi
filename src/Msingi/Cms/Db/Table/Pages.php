<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\Table;
use Zend\Db\Sql\Select;

class Pages extends Table
{
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_pages',
            'object' => 'Msingi\Cms\Model\Page',
            'fields' => array(
                'type' => 'string',
                'path' => 'string',
                'template' => 'string'
            )
        );
    }

    /**
     *
     *
     * @param $name
     * @param $language
     * @return array
     */
    public function fetchPage($path, $parent_id = null)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($path, $parent_id) {
            $select->where(array('type' => 'static', 'path' => $path, 'parent_id' => $parent_id));
        });

        return $rowset->current();
    }

    /**
     * @param $route
     */
    public function fetchOrCreate($route)
    {
        $rowset = $this->tableGateway->select(array('type' => 'mvc', 'path' => $route));

        $page = $rowset->current();

        if ($page == null) {
            $this->tableGateway->insert(array(
                'type' => 'mvc',
                'path' => $route,
                'parent_id' => 1
            ));

            $rowset = $this->tableGateway->select(array('id' => $this->tableGateway->lastInsertValue));
            $page = $rowset->current();
        }

        return $page;
    }
}