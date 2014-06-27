<?php

namespace Msingi\Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Msingi\Cms\Entity\MailTemplateI18n;

/**
 * Class MailTemplatesI18n
 *
 * @package Msingi\Cms\Repository
 */
class MailTemplatesI18n extends EntityRepository
{
    /**
     * @param \Msingi\Cms\Entity\MailTemplate $template
     * @param string $language
     * @return MailTemplateI18n|null|object
     */
    public function fetchOrCreate($template, $language)
    {
        $i18n = $this->findOneBy(array('parent' => $template, 'language' => $language));

        if ($i18n == null) {
            $i18n = new MailTemplateI18n();
            $i18n->setParent($template);
            $i18n->setLanguage($language);

            $this->getEntityManager()->persist($i18n);

            $this->getEntityManager()->flush();
        }

        return $i18n;
    }

}
