<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ArticleI18n
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cms_articles_i18n", indexes={
 * @ORM\Index(columns={"parent_id", "language"})
 * })
 */
class ArticleI18n
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Article
     */
    protected $parent;

    /**
     * @ORM\Column(type="string", length=2)
     * @var string
     */
    protected $language;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param Article $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Article
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
