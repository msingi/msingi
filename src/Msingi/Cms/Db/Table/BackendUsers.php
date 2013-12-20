<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\Table;

class BackendUsers extends Table
{
    /**
     * @return array
     */
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_backend_users',
            'object' => 'Msingi\Cms\Model\Backend\User',
            'fields' => array(
                'username' => 'string',
                'name' => 'name',
            )
        );
    }

    /**
     * @param $username
     * @return array|\ArrayObject|null
     */
    public function fetchByUsername($username)
    {
        $rowset = $this->tableGateway->select(array('username' => $username));

        return $rowset->current();

    }
}