<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PageFragmentI18n
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\PageFragmentsI18n")
 * @ORM\Table(name="cms_page_fragments_i18n", indexes={
 * @ORM\Index(columns={"parent_id", "language"}),
 * })
 */
class PageFragmentI18n
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Msingi\Cms\Entity\PageFragment")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \Msingi\Cms\Entity\PageFragment
     */
    protected $parent = null;

    /**
     * @ORM\Column(type="string", length=2)
     * @var string
     */
    protected $language = '';

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $content = '';

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
     * @param \Msingi\Cms\Entity\Page $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \Msingi\Cms\Entity\Page
     */
    public function getParent()
    {
        return $this->parent;
    }


}
