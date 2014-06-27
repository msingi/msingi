<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Msingi\Cms\Entity\Enum\PageType;
use Msingi\Cms\Entity\Page;

/**
 * Class Pages
 *
 * @package Msingi\Cms\Repository
 */
class Pages extends EntityRepository
{
    /**
     * @param string $route
     * @return \Msingi\Cms\Entity\Page
     */
    public function fetchOrCreate($route)
    {
        $page = $this->findOneBy(array('type' => PageType::PAGE_MVC, 'path' => $route));

        if ($page == null) {
            $root = $this->find(1);
            if ($root == null) {
                $root = new Page();
                $root->setId(1);
                $root->setType(PageType::PAGE_MVC);
                $root->setPath('frontend/index');
                $root->setTemplate('frontend/index');

                $this->getEntityManager()->persist($root);
            }

            $page = new Page();

            $page->setParent($root);
            $page->setType(PageType::PAGE_MVC);
            $page->setPath($route);
            $page->setTemplate($route);

            $this->getEntityManager()->persist($page);

            $this->getEntityManager()->flush();
        }

        return $page;
    }

    /**
     * @param string $path
     * @param \Msingi\Cms\Entity\Page $parent
     */
    public function fetchPage($path, $parent)
    {
        $page = $this->findOneBy(array('type' => PageType::PAGE_STATIC, 'path' => $path, 'parent' => $parent));

        return $page;
    }

    /**
     *
     */
    public function fetchTree()
    {
//        $qb = $this->createQueryBuilder('p');
//
//        $qb->select(array('p.id', 'p.path', 'p.type', 'parent.id AS parent_id'))
//            ->leftJoin('p.parent', 'parent');
//
//        $pages = array();
//
//        foreach ($qb->getQuery()->getResult() as $pageRow) {
//
//            if($pageRow['type'] == PageType::PAGE_MVC) {
//                $path = explode('/', $pageRow['path'];
//            }
//        }
//
//        return $pages;
    }
}
