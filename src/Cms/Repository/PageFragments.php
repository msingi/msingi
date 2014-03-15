<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PageFragments
 *
 * @package Msingi\Cms\Repository
 */
class PageFragments extends EntityRepository
{
    /**
     * @param \Msingi\Cms\Entity\Page $page
     * @param string $language
     * @return array
     */
    public function fetchFragmentsArray($page, $language)
    {
        $qb = $this->createQueryBuilder('f');

        $qb->select(array('f.name', 'fl.content'))
            ->leftJoin('f.i18n', 'fl')
            ->where('f.page = :page')
            ->andWhere('fl.language = :language');

        $qb->setParameters(array('page' => $page, 'language' => $language));

        $fragments = array();
        foreach ($qb->getQuery()->getResult() as $row) {
            $fragments[$row['name']] = $row['content'];
        }

        return $fragments;
    }
}