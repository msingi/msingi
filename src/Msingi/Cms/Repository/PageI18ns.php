<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Msingi\Cms\Entity\Enum\PageType;
use Msingi\Cms\Entity\Page;
use Msingi\Cms\Entity\PageI18n;

/**
 * Class PageI18ns
 *
 * @package Msingi\Cms\Repository
 */
class PageI18ns extends EntityRepository
{
    /**
     * @param \Msingi\Cms\Entity\Page $page
     * @param string $language
     * @return \Msingi\Cms\Entity\PageI18n
     */
    public function fetchOrCreate($page, $language)
    {
        $i18n = $this->findOneBy(array('parent' => $page, 'language' => $language));

        if ($i18n == null) {
            $i18n = new PageI18n();
            $i18n->setParent($page);
            $i18n->setLanguage($language);

            $this->getEntityManager()->persist($i18n);

            $this->getEntityManager()->flush();
        }

        return $i18n;
    }
}