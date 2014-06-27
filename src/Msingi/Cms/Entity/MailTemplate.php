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

    /**
     * @ORM\OneToMany(targetEntity="\Msingi\Cms\Entity\MailTemplateI18n",mappedBy="parent")
     * @var \Msingi\Cms\Entity\MailTemplateI18n[]
     */
    protected $i18n = null;

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $tokens
     */
    public function setTokens($tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return string
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}
