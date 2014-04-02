<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Msingi\Cms\Entity\MailTemplate;

/**
 * Class MailTemplates
 *
 * @package Msingi\Cms\Repository
 */
class MailTemplates extends EntityRepository
{
    /**
     * @param string $templateName
     */
    public function fetchOrCreate($templateName)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select()->where('t.name = :name');
        $qb->setParameter('name', $templateName);
        $qb->getQuery()->useResultCache(true);

        /** @var \Msingi\Cms\Entity\MailTemplate $template */
        $template = $qb->getQuery()->getOneOrNullResult();
        if (!$template) {
            $template = new MailTemplate();
            $template->setName($templateName);

            $this->getEntityManager()->persist($template);
            $this->getEntityManager()->flush();
        }

        return $template;
    }
}