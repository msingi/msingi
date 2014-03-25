<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Msingi\Cms\Entity\Setting;

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

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select()->where('s.name = :name');

        $qb->setParameter('name', $name);

        $setting = $qb->getQuery()->getOneOrNullResult();
        if ($setting == null) {
            $setting = new Setting();
            $setting->setName($name);

            $this->getEntityManager()->persist($setting);
        }

        $setting->setValue($value);

        $this->getEntityManager()->flush();
    }
}