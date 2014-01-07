<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Select;

abstract class AbstractEntitiesController extends AuthenticatedController
{
    /**
     * Get storage
     *
     * @return \Msingi\Db\Table
     */
    abstract protected function getTable();

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
    abstract protected function getPaginatorQuery($filter = null);

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
     * @return array|ViewModel|void
     */
    public function indexAction()
    {
        $table = $this->getTable();

        // create a new pagination adapter object
        $paginatorAdapter = new DbSelect($this->getPaginatorQuery(), $table->getAdapter(), $table->getResultSetPrototype());

        $paginator = new Paginator($paginatorAdapter);
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
        if ($form == null)
            return $this->redirect()->toUrl($this->getActionUrl('index'));

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
            return $this->redirect()->toUrl($this->getActionUrl('index'));

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

                if (intval($values['id']) == 0) {
                    // create new entity
                    $entity = $this->getTable()->createRow($values);
                } else {
                    // try to fetch existing entity
                    $entity = $this->getTable()->fetchById($values['id']);
                    if ($entity == null) {
                        return $this->redirect()->toUrl($this->getActionUrl('index'));
                    }

                    // update entity valuess
                    $entity->setValues($values);
                    $this->getTable()->save($entity);
                }

                $this->onEntitySave($entity, $values);

                // redirect back to index action
                return $this->redirect()->toUrl($this->getActionUrl('index'));
            } else {
                // try to fetch entity
                $entity = $this->getTable()->fetchById($this->params()->fromPost('id'));
                if ($entity == null)
                    return $this->redirect()->toUrl($this->getActionUrl('index'));
            }
        } else {
            // try to fetch entity
            $entity = $this->getTable()->fetchById($this->params()->fromQuery('id'));
            if ($entity == null)
                return $this->redirect()->toUrl($this->getActionUrl('index'));

            // set form data
            $form->setData($entity->getArrayCopy());
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
        return $this->redirect()->toUrl($this->getActionUrl('index'));
    }

    /**
     * Called after the entity is created and saved
     * Used for processing extra data as depended objects of file attachements
     *
     * @param $entity
     * @param $values
     */
    protected function onEntitySave($entity, $values)
    {

    }
}