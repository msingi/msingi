<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PageTemplates
 *
 * @package Msingi\Cms\Repository
 */
class PageTemplates extends EntityRepository
{
    /**
     * @return array
     */
    public function fetchOptions()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t.name', 't.label')->orderBy('t.name');

        $ret = array();
        foreach ($qb->getQuery()->getResult() as $row) {
            $ret[$row['name']] = $row['label'];
        }

        return $ret;
    }
}