<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class Menus
 *
 * @package Msingi\Cms\Repository
 */
class Menus extends EntityRepository
{
    /**
     * Fetch menu as array for use with Navigation
     *
     * @param string $name
     * @param string $language
     * @return array
     */
    public function fetchMenuArray($name, $language)
    {
        $qb = $this->createQueryBuilder('m');

        /* @todo optimize query */
        $qb->select(array('m.id', 'mp.id AS parent_id', 'm.route', 'm.params', 'ml.label'))
            ->leftJoin('m.parent', 'mp')
            ->leftJoin('m.i18n', 'ml', 'WITH', 'ml.language = :language')
            ->where('m.menu = :name')
            ->orderBy('m.order', 'ASC');

        $qb->setParameters(array('name' => $name, 'language' => $language));

        $qb->getQuery()->useResultCache(true);

        $pages = array();
        foreach ($qb->getQuery()->getResult() as $row) {

            $page = array(
                'id' => $row['id'],
                'label' => $row['label'],
            );

            if ($row['route'] != '') {
                $page['route'] = $row['route'];
                // parse route parameters
                $params = array();
                parse_str(trim($row['params']), $params);
                $page['params'] = $params;
            }

            $parent_id = intval($row['parent_id']);

            if (!isset($pages[$parent_id])) {
                $pages[$parent_id] = array();
            }

            $pages[$parent_id][$row['id']] = $page;
        }

        // nothing fetched
        if (empty($pages))
            return array();

        // attach subpages
        foreach ($pages as $parent_id => $subpages) {
            if ($parent_id == 0)
                continue;

            $pages[0][$parent_id]['pages'] = $subpages;
        }

        // return root
        return $pages[0];
    }

    /**
     * @param $menu
     * @return int
     */
    public function fetchMaxOrder($menu)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('max_order', 'max_order', 'integer');

        $query = $this->getEntityManager()->createNativeQuery('SELECT MAX(`order`) AS max_order FROM cms_menu AS m WHERE menu = :menu', $rsm);
        $query->setParameter('menu', $menu);

        $res = $query->getOneOrNullResult();

        return $res['max_order'];
    }

}