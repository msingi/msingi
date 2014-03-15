<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MailTemplateI18n
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cms_mail_templates_i18n", indexes={
 * @ORM\Index(columns={"parent_id", "language"}),
 * })
 */
class MailTemplateI18n
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Msingi\Cms\Entity\MailTemplate")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \Msingi\Cms\Entity\Page
     */
    protected $parent = null;

    /**
     * @ORM\Column(type="string", length=2)
     * @var string
     */
    protected $language = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $subject = '';

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $template = '';
}