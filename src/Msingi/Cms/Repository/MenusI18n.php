<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Msingi\Cms\Entity\MenuI18n;

/**
 * Class MenusI18n
 *
 * @package Msingi\Cms\Repository
 */
class MenusI18n extends EntityRepository
{
    /**
     * @param $item
     * @param $language
     * @param $label
     */
    public function setLabel($item, $language, $label)
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select()->where('l.language = :language')->andWhere('l.parent = :item');
        $qb->setParameters(array(
            'item' => $item,
            'language' => $language,
        ));

        $i18n = $qb->getQuery()->getOneOrNullResult();
        if (!$i18n) {
            $i18n = new MenuI18n();
            $i18n->setParent($item);
            $i18n->setLanguage($language);
        }

        $i18n->setLabel($label);

        $this->getEntityManager()->persist($i18n);
        $this->getEntityManager()->flush();
    }
}
