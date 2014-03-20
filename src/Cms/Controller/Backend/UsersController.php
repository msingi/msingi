<?php

namespace Msingi\Cms\Controller\Backend;

use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Msingi\Cms\Form\Backend\UserForm;
use Msingi\Util\PasswordGenerator;

class UsersController extends AbstractEntitiesController
{
    /**
     * Return class name of managed entities
     *
     * @return string
     */
    protected function getEntityClass()
    {
        return 'Msingi\Cms\Entity\BackendUser';
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
     * @return DoctrinePaginator
     */
    protected function getPaginatorAdapter($filter = null)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('b')->from($this->getEntityClass(), 'b');

        return new DoctrinePaginator(new Paginator($queryBuilder->getQuery()));
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

    /**
     * @return string
     */
    protected function getIndexRoute()
    {
        return 'backend/admins';
    }
}