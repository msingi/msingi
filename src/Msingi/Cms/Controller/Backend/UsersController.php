<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Form\Backend\UserForm;
use Msingi\Util\PasswordGenerator;

/**
 * Class UsersController - manage backend users
 *
 * @package Msingi\Cms\Controller\Backend
 */
class UsersController extends AbstractEntitiesController
{
    protected $entityClass = 'Msingi\Cms\Entity\BackendUser';
    protected $indexRoute = 'backend/admins';

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
     * @param UserForm $form
     * @param \Msingi\Cms\Entity\BackendUser $entity
     */
    protected function populateForm($form, $entity)
    {
        $form->get('name')->setValue($entity->getName());
        $form->get('username')->setValue($entity->getUsername());
        $form->get('email')->setValue($entity->getEmail());
        $form->get('role')->setValue($entity->getRole());
    }

    /**
     * @param \Msingi\Cms\Entity\BackendUser $entity
     * @param UserForm $form
     */
    protected function updateEntity($entity, $form)
    {
        $data = $form->getData();

        $entity->setName($data['name']);
        $entity->setUsername($data['username']);
        $entity->setEmail($data['email']);
        $entity->setRole($data['role']);
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
