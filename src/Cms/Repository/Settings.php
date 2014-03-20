<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Settings
 *
 * @package Msingi\Cms\Repository
 */
class Settings extends EntityRepository
{
    /**
     * Fetch all settings as associative array
     *
     * @return array
     */
    public function fetchArray()
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select(array('s.name', 's.value'));

        $result = array();
        foreach ($qb->getQuery()->getResult() as $row) {
            $result[$row['name']] = $row['value'];
        }

        return $result;
    }
}