<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class BackendUsers
 *
 * @package Msingi\Cms\Repository
 */
class BackendUsers extends EntityRepository
{
    /**
     * @param string $username
     * @return \Msingi\Cms\Entity\BackendUser
     */
    public function findUser($username)
    {
        $qb = $this->createQueryBuilder('bu');
        $qb->select()->where('bu.username = :username');
        $qb->setParameter('username', $username);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
