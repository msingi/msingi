<?php

namespace Msingi\Cms\Controller\Backend\Doctrine;

use Msingi\Cms\Controller\Backend\AuthenticatedController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

abstract class AbstractEntitiesController extends AuthenticatedController
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

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
     * Get paginator adapter
     *
     * @param array|null $filter
     * @return DoctrinePaginator|Select
     */
    abstract protected function getPaginatorAdapter($filter = null);

    /**
     * @return string
     */
    abstract protected function getIndexRoute();

    /**
     * @param \Zend\Form\Form $form
     * @param $entity
     */
    abstract protected function populateForm($form, $entity);

    /**
     * @param $entity
     * @param \Zend\Form\Form $form
     */
    abstract protected function updateEntity($entity, $form);

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
     * Get storage
     *
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->getEntityClass());
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

                //
                $classname = $this->getEntityClass();

                // get form data
                $values = $form->getData();

                if (!isset($values['id']) || intval($values['id']) == 0) {
                    // create new entity
                    $entity = $this->createEntity($values, $form);
                } else {
                    // load the entity
                    $entity = $this->getEntityManager()->find($classname, $values['id']);
                    if ($entity == null) {
                        return $this->redirect()->toRoute($this->getIndexRoute());
                    }
                }

                // update entity values
                $this->updateEntity($entity, $form);

                //
                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                //
                $this->onEntitySaved($entity, $values);

                // redirect back to index action
                return $this->redirect()->toRoute($this->getIndexRoute());
            } else {
//
//                var_dump($this->params()->fromPost());
//
//                die;
//                // try to fetch entity?
//                $entity = $this->getEntityManager()->find($this->getEntityClass(), $this->params()->fromPost('id'));
//                if ($entity == null)
//                    return $this->redirect()->toRoute($this->getIndexRoute());
            }
        } else {
            // try to fetch entity
            $entity = $this->getEntityManager()->find($this->getEntityClass(), $this->params()->fromQuery('id'));
            if ($entity == null)
                return $this->redirect()->toRoute($this->getIndexRoute());

            //
            $form->get('id')->setValue($entity->getId());

            // set form data
            //$form->setEntity($entity);
            $this->populateForm($form, $entity);
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
        $entity = $this->getEntityManager()->find($this->getEntityClass(), $this->params()->fromQuery('id'));
        if ($entity != null) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }

        return $this->redirect()->toRoute($this->getIndexRoute());
    }

    /**
     * @param $values
     * @param $form
     * @return array|object
     */
    protected function createEntity($values, $form)
    {
        // create new entity
        $entity = $this->getServiceLocator()->get($this->getEntityClass());

        //$entity->setEntityManager($this->getEntityManager());

        return $entity;
    }

    /**
     * Extra processing of entity values
     *
     * @param $entity
     * @param $values
     */
    protected function onEntitySaved($entity, $values)
    {
    }
}