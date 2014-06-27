<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Entity\Enum\PageType;
use Msingi\Cms\Entity\Menu;
use Zend\View\Model\ViewModel;

/**
 * Class MenuController
 * @package Msingi\Cms\Controller\Backend
 */
class MenuController extends AuthenticatedController
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /**
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $settings = $this->getServiceLocator()->get('Settings');

        $language = $settings->get('frontend:languages:default');

        /** @var \Msingi\Cms\Repository\Menus $menu_repository */
        $menu_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Menu');
        /** @var \Msingi\Cms\Repository\Pages $pages_repository */
        $pages_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');

        $menus = array();
        foreach ($config['menus'] as $menu => $label) {
            $menus[$menu] = array(
                'label' => $label,
                'menu' => $menu_repository->fetchMenuArray($menu, $language),
            );
        }

        return new ViewModel(array(
            'root' => $pages_repository->find(1),
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

        /** @var \Msingi\Cms\Repository\Menus $menu_repository */
        $menu_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Menu');

        $vm = new ViewModel(array(
            'menu' => $menu_repository->fetchMenuArray($menu, $language),
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
        /** @var \Msingi\Cms\Repository\Pages $pages_repository */
        $pages_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');

        $menu = $this->params()->fromPost('menu');
        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $pages_repository->find($this->params()->fromPost('page'));

        if ($page->getType() == PageType::PAGE_STATIC) {
            $route = 'frontend/index/page';
            $params = 'path=' . $page->getPath();
        } else {
            $route = $page->getPath();
            $params = '';
        }

        //$this->getMenuTable()->addPage($menu, $route, $params);

        /** @var \Msingi\Cms\Repository\Menus $menu_repository */
        $menu_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Menu');

        $order = $menu_repository->fetchMaxOrder($menu);

        $menu_item = new Menu();
        $menu_item->setMenu($menu);
        $menu_item->setOrder($order + 1);
        $menu_item->setRoute($route);
        $menu_item->setParams($params);

        $this->getEntityManager()->persist($menu_item);
        $this->getEntityManager()->flush();

        return $this->getResponse();
    }

    /**
     * Add page to menu
     */
    public function deleteAction()
    {
        /** @var \Msingi\Cms\Repository\Menus $menu_repository */
        $menu_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Menu');

        $item = $menu_repository->find($this->params()->fromPost('item'));
        if ($item) {
            $this->getEntityManager()->remove($item);
            $this->getEntityManager()->flush();
        }

        return $this->getResponse();
    }

    /**
     *
     */
    public function sortAction()
    {
        /** @var \Msingi\Cms\Repository\Menus $menu_repository */
        $menu_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Menu');

        $menu = $this->params()->fromPost('menu');
        $data = $this->params()->fromPost('data');

        $order = 1;
        foreach ($data as $item) {
            if (intval($item['item_id']) == 0)
                continue;

            $item = $menu_repository->find($item['item_id']);
            if ($item) {
                $item->setOrder($order++);
            }
        }

        $this->getEntityManager()->flush();

        return $this->getResponse();
    }

    /**
     *
     */
    public function labelAction()
    {
        $language = $this->params()->fromPost('name');
        $label = $this->params()->fromPost('value');

        /** @var \Msingi\Cms\Repository\Menus $menu_repository */
        $menu_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Menu');

        $item = $menu_repository->find($this->params()->fromPost('pk'));

        /** @var \Msingi\Cms\Repository\MenusI18n $labels_repository */
        $labels_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\MenuI18n');

        $labels_repository->setLabel($item, $language, $label);

        return $this->getResponse();
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
}
