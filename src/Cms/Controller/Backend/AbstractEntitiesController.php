<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

/**
 * Class AbstractEntitiesController
 * @package Msingi\Cms\Controller\Backend
 */
abstract class AbstractEntitiesController extends AuthenticatedController
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /** @var string */
    protected $entityClass;

    /** @var string */
    protected $indexRoute;

    /**
     * Get edit form, null if add/edit is not required
     *
     * @return \Zend\Form\Form|null
     */
    abstract protected function getEditForm();

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
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->entityClass);
    }

    /**
     * Get paginator adapter
     *
     * @param array|null $filter
     * @return \Doctrine\ORM\Query
     */
    protected function getPaginatorQuery($filter = null)
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder('e');

        return $queryBuilder->select()->getQuery();
    }

    /**
     * @return array|ViewModel|void
     */
    public function indexAction()
    {
        $query = $this->getPaginatorQuery();

        $paginator = new Paginator(new DoctrinePaginator(new \Doctrine\ORM\Tools\Pagination\Paginator($query)));
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
            return $this->redirect()->toRoute($this->indexRoute);
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
            return $this->redirect()->toRoute($this->indexRoute);

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
                    // create new entity
                    $entity = $this->createEntity($values, $form);
                } else {
                    // load the entity
                    $entity = $this->getRepository()->find($values['id']);
                    if ($entity == null) {
                        return $this->redirect()->toRoute($this->indexRoute);
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
                return $this->redirect()->toRoute($this->indexRoute);
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
            $entity = $this->getRepository()->find($this->params()->fromQuery('id'));
            if ($entity == null)
                return $this->redirect()->toRoute($this->indexRoute);

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
     * Delete entity
     *
     * @return array|ViewModel|void
     */
    public function deleteAction()
    {
        $entity = $this->getRepository()->find($this->params()->fromQuery('id'));
        if ($entity != null) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }

        return $this->redirect()->toRoute($this->indexRoute);
    }

    /**
     * @param $values
     * @param $form
     * @return array|object
     */
    protected function createEntity($values, $form)
    {
        // create new entity
        $entity = $this->getServiceLocator()->get($this->entityClass);

        return $entity;
    }

    /**
     * Fill form with the data
     *
     * @param \Zend\Form\Form $form
     * @param $entity
     */
    protected function populateForm($form, $entity)
    {
        //
        $form->get('id')->setValue($entity->getId());
    }

    /**
     * Update entity from the form data
     *
     * @param $entity
     * @param \Zend\Form\Form $form
     */
    protected function updateEntity($entity, $form)
    {
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