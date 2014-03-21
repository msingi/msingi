<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Msingi\Cms\Entity\PageFragmentI18n;

/**
 * Class PageFragmentsI18n
 *
 * @package Msingi\Cms\Repository
 */
class PageFragmentsI18n extends EntityRepository
{
    /**
     * @param \Msingi\Cms\Entity\PageFragment $page_fragment
     * @param string $language
     * @return \Msingi\Cms\Entity\PageFragmentI18n
     */
    public function fetchOrCreate($page_fragment, $language)
    {
        $i18n = $this->findOneBy(array('parent' => $page_fragment, 'language' => $language));

        if ($i18n == null) {
            $i18n = new PageFragmentI18n();
            $i18n->setParent($page_fragment);
            $i18n->setLanguage($language);

            $this->getEntityManager()->persist($i18n);

            $this->getEntityManager()->flush();
        }

        return $i18n;
    }
}