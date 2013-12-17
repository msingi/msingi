<?php

namespace Msingi\Cms\Model\Table;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class Pages
{
    protected $tableGateway;

    /**
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
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