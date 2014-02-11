<?php

namespace Msingi\Cms\Controller\Backend\Doctrine;

use Msingi\Cms\Controller\Backend\AuthenticatedController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Zend\Db\Sql\Select;
use Doctrine\ORM\EntityManager;

abstract class AbstractEntitiesController extends AuthenticatedController
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /**
     * Get storage
     *
     */
    abstract protected function getRepository();

    /**
     * @return string
     */
    abstract protected function getEntityClass();

    /**
     * Get edit form, null if add/edit is not required
     *
     * @return \Zend\Form\Form|null
     */
    abstract protected function getEditForm();

    /**
     * Get query for paginator adapter
     *
     * @param $request
     * @param $filter
     * @return Select
     */
    abstract protected function getPaginatorAdapter($filter = null);

    /**
     * @return string
     */
    abstract protected function getIndexRoute();

    /**
     * Get count of items for paginator
     *
     * @return int
     */
    protected function getItemsCountPerPage()
    {
        return 10;
    }

    /**
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }

    /**
     * @return array|ViewModel|void
     */
    public function indexAction()
    {
        // create a new pagination adapter object
        //$paginatorAdapter = new DbSelect($this->getPaginatorQuery(), $table->getAdapter(), $table->getResultSetPrototype());

        $paginator = new Paginator($this->getPaginatorAdapter());
        $paginator->setItemCountPerPage($this->getItemsCountPerPage());
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    /**
     * Add new entity
     *
     * @return ViewModel
     */
    public function addAction()
    {
        $form = $this->getEditForm();
        if ($form == null) {
            return $this->redirect()->toRoute($this->getIndexRoute());
        }

        $vm = new ViewModel(array(
            'form' => $form
        ));

        return $vm;
    }

    /**
     * Edit/save entity
     *
     * @return ViewModel
     */
    public function editAction()
    {
        $form = $this->getEditForm();
        if ($form == null)
            return $this->redirect()->toRoute($this->getIndexRoute());

        $request = $this->getRequest();
        if ($request->isPost()) {

            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            // check if form data is valid
            if ($form->isValid()) {

                // get form data
                $values = $form->getData();

                if (!isset($values['id']) || intval($values['id']) == 0) {
                    $this->createEntity($values);
                } else {
                    $this->updateEntity($values);
                }

                // redirect back to index action
                return $this->redirect()->toRoute($this->getIndexRoute());
            } else {
                // try to fetch entity
                $entity = $this->getTable()->fetchById($this->params()->fromPost('id'));
                if ($entity == null)
                    return $this->redirect()->toRoute($this->getIndexRoute());
            }
        } else {
            // try to fetch entity
            $entity = $this->getEntityManager()->find($this->getEntityClass(), $this->params()->fromQuery('id'));
            if ($entity == null)
                return $this->redirect()->toRoute($this->getIndexRoute());

            // set form data
            $form->setEntity($entity);
        }

        //
        $vm = new ViewModel(array(
            'entity' => $entity,
            'form' => $form
        ));

        return $vm;
    }

    /**
     * Delete entity by id
     *
     * @return array|ViewModel|void
     */
    public function deleteAction()
    {
        $entity = $this->getTable()->fetchById($this->params()->fromQuery('id'));
        if ($entity != null) {
            $this->getTable()->delete(array('id' => $entity->id));
        }

        return $this->redirect()->toRoute($this->getIndexRoute());
    }

    /**
     * @param $values
     * @return null
     */
    protected function createEntity($values)
    {
        // create new entity
        $entity = $this->getTable()->createRow($values);
        if ($entity == null)
            return null;

        $this->onEntityCreate($entity, values);

        return $entity;
    }

    /**
     * @param $values
     * @return null
     */
    protected function updateEntity($values)
    {
        // try to fetch existing entity
        $entity = $this->getTable()->fetchById($values['id']);
        if ($entity == null)
            return null;

        // update entity valuess
        $entity->setValues($values);
        $this->getTable()->save($entity);

        $this->onEntityUpdate($entity, $values);

        return $entity;
    }

    /**
     *
     * @param $values
     * @return $entity
     */
    protected function onEntityCreate($entity, $values)
    {
    }

    /**
     * Called after the entity is created and saved
     * Used for processing extra data as depended objects of file attachements
     *
     * @param $entity
     * @param $values
     */
    protected function onEntityUpdate($values)
    {
    }

}