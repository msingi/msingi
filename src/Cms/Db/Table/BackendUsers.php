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
                'name' => 'string',
                'username' => 'string',
                'email' => 'string',
                'role' => 'string'
            )
        );
    }

    /**
     * @param $username
     * @return array|\ArrayObject|null
     */
    public function fetchByUsername($username)
    {
        $rowset = $this->select(array('username' => $username));

        return $rowset->current();

    }
}