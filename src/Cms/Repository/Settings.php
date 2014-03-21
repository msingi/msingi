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
            $value = @unserialize($row['value']);
            $result[$row['name']] = ($value !== false) ? $value : $row['value'];
        }

        return $result;
    }
}