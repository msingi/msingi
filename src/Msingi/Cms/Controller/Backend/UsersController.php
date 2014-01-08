<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Form\Backend\UserForm;
use Msingi\Util\PasswordGenerator;
use Zend\Db\Sql\Select;

class UsersController extends AbstractEntitiesController
{
    protected $usersTable;

    /**
     * Get storage
     *
     * @return \Msingi\Db\Table
     */
    protected function getTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Msingi\Cms\Db\Table\BackendUsers');
        }

        return $this->usersTable;
    }

    /**
     * Get edit form, null if add/edit is not required
     *
     * @return \Zend\Form\Form|null
     */
    protected function getEditForm()
    {
        return new UserForm();
    }

    /**
     * Get query for paginator adapter
     *
     * @param $request
     * @param $filter
     * @return Select
     */
    protected function getPaginatorQuery($filter = null)
    {
        $select = $this->getTable()->getSql()->select();

        return $select;
    }

    /**
     * @param $user
     * @param $values
     */
    protected function onEntitySave($user, $values)
    {
        if ($values['password'] != '' && $values['password'] == $values['password_confirm']) {

            $config = $this->getServiceLocator()->get('Config');

            $salt = $config['backend']['auth']['salt'];

            $password_salt = PasswordGenerator::generate(10);

            $new_password = sha1($salt . $values['password'] . $password_salt);

            $this->getTable()->update(array(
                'password' => $new_password,
                'password_salt' => $password_salt
            ), array(
                'id' => $values['id']
            ));
        }
    }
}