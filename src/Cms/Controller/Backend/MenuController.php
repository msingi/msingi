<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\View\Model\ViewModel;

/**
 * Class MenuController
 * @package Msingi\Cms\Controller\Backend
 */
class MenuController extends AuthenticatedController
{
    /**
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $settings = $this->getServiceLocator()->get('Settings');

        $language = $settings->get('frontend:languages:default');

        $menus = array();
        foreach ($config['menus'] as $menu => $label) {
            $menus[$menu] = array(
                'label' => $label,
                'menu' => $this->getMenuTable()->fetchMenu($menu, $language),
            );
        }

        return new ViewModel(array(
            'pages' => $this->getPagesTable()->fetchTree(),
            'menus' => $menus,
            'languages' => $settings->get('frontend:languages:enabled'),
            'language' => $language
        ));
    }

    /**
     * @return ViewModel
     */
    public function loadAction()
    {
        $menu = $this->params()->fromPost('menu');
        $language = $this->params()->fromPost('language');

        $vm = new ViewModel(array(
            'menu' => $this->getMenuTable()->fetchMenu($menu, $language, false)
        ));

        // disable layout
        $vm->setTerminal(true);

        return $vm;
    }

    /**
     * Add page to menu
     */
    public function addAction()
    {
        $menu = $this->params()->fromPost('menu');
        $page = $this->getPagesTable()->fetchById($this->params()->fromPost('page'));

        if ($page->type == 'static') {
            $route = 'frontend/index/page';
            $params = 'path=' . $page->path;
        } else {
            $route = $page->path;
            $params = '';
        }

        $this->getMenuTable()->addPage($menu, $route, $params);

        return $this->getResponse();
    }

    /**
     * Add page to menu
     */
    public function deleteAction()
    {
        $item = $this->params()->fromPost('item');

        $this->getMenuTable()->deleteItem($item);

        return $this->getResponse();
    }

    /**
     *
     */
    public function sortAction()
    {
        $menu = $this->params()->fromPost('menu');
        $data = $this->params()->fromPost('data');

        $order = 0;
        foreach ($data as $item) {
            if (intval($item['item_id']) == 0)
                continue;

            $this->getMenuTable()->update(array(
                'parent_id' => intval($item['parent_id']) != 0 ? intval($item['parent_id']) : null,
                'order' => $order++
            ), array(
                'id' => $item['item_id']
            ));
        }

        return $this->getResponse();
    }

    /**
     *
     */
    public function labelAction()
    {
        $language = $this->params()->fromPost('name');
        $item = $this->params()->fromPost('pk');
        $label = $this->params()->fromPost('value');

        $this->getMenuTable()->setLabel($item, $language, $label);

        return $this->getResponse();
    }
}