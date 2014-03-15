<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MailTemplate
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\MailTemplates")
 * @ORM\Table(name="cms_mail_templates", indexes={
 * @ORM\Index(columns={"name"}),
 * })
 */
class MailTemplate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=50)
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $description = '';

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $tokens = '';
}