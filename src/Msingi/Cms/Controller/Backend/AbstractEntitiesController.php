<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Doctrine\EntityManagerAwareInterface;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

/**
 * Class AbstractEntitiesController
 *
 * Manage lists of entities in simple way
 *
 * @package Msingi\Cms\Controller\Backend
 */
abstract class AbstractEntitiesController extends AuthenticatedController
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /** @var  \Doctrine\ORM\EntityRepository */
    protected $entityRepository;

    /** @var string */
    protected $entityClass;

    /** @var string */
    protected $indexRoute;

    /** @var int */
    protected $itemsPerPage = 10;

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @return string
     */
    public function getIndexRoute()
    {
        return $this->indexRoute;
    }

    /**
     * Get edit form, null if add/edit is not required
     *
     * @return \Zend\Form\Form|null
     */
    protected function getEditForm()
    {
        return null;
    }

    /**
     * Get count of items for paginator
     *
     * @return int
     */
    protected function getItemsCountPerPage()
    {
        return $this->itemsPerPage;
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
        if (null === $this->entityRepository) {
            $this->entityRepository = $this->getEntityManager()->getRepository($this->entityClass);
        }

        return $this->entityRepository;
    }

    /**
     * Get query for paginator
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
        // get form
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

                // save updates
                $this->getEntityManager()->flush();

                //
                $this->onEntitySaved($entity, $values);

                // redirect back to index action
                return $this->redirect()->toRoute($this->indexRoute);
            }
        } else {
            // try to fetch entity
            $entity = $this->getRepository()->find($this->params()->fromQuery('id'));
            if ($entity == null)
                return $this->redirect()->toRoute($this->indexRoute);

            // set form data
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
     * @return null|object
     */
    protected function getEntity()
    {
        return $this->getRepository()->find($this->params()->fromQuery('id'));
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

        if ($entity instanceof EntityManagerAwareInterface) {
            $entity->setEntityManager($this->getEntityManager());
        }

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
