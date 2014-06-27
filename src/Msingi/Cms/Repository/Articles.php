<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Msingi\Cms\Entity\Enum\ArticleStatus;

/**
 * Class Articles
 *
 * @package Msingi\Cms\Repository
 */
class Articles extends EntityRepository
{
    /**
     * @param int $limit
     * @return \Msingi\Cms\Entity\Article[]
     */
    public function fetchLastNews($limit = 3)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select()
            ->where('a.status = :status')
            ->orderBy('a.date', 'DESC');

        $query = $qb->getQuery();

        $query->useQueryCache(true);

        $query->setParameter('status', ArticleStatus::STATUS_PUBLISHED);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return \Doctrine\ORM\Query
     */
    public function selectPublished($year = 0, $month = 0, $day = 0)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'Msingi\Doctrine\DBAL\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'Msingi\Doctrine\DBAL\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'Msingi\Doctrine\DBAL\Day');

        $qb = $this->createQueryBuilder('a');

        $qb->select()
            ->where('a.status = :status')
            ->orderBy('a.date', 'DESC');

        if ($year != 0) {
            $qb->andWhere('YEAR(a.date) = :year');
            $qb->setParameter('year', $year);
        }

        if ($month != 0) {
            $qb->andWhere('MONTH(a.date) = :month');
            $qb->setParameter('month', $month);
        }

        if ($day != 0) {
            $qb->andWhere('DAY(a.date) = :day');
            $qb->setParameter('day', $day);
        }

        //
        $query = $qb->getQuery();

        $query->setParameter('status', ArticleStatus::STATUS_PUBLISHED);

        $query->useQueryCache(true);

        return $query;
    }
}
