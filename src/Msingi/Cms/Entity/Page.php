<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Page
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\Pages")
 * @ORM\Table(name="cms_pages", indexes={
 * @ORM\Index(columns={"type"}),
 * @ORM\Index(columns={"path"}),
 * })
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Msingi\Cms\Entity\Page")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \Msingi\Cms\Entity\Page
     */
    protected $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="Msingi\Cms\Entity\Page", mappedBy="parent")
     * @ORM\OrderBy({"type" = "DESC", "path" = "ASC"})
     * @var \Msingi\Cms\Entity\Page[]
     */
    protected $children = null;

    /**
     * @ORM\Column(type="page_type")
     * @var string
     */
    protected $type = 'mvc';

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    protected $path = '';

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    protected $template = '';

    /**
     * @ORM\OneToMany(targetEntity="Msingi\Cms\Entity\PageFragment", mappedBy="page")
     * @var \Msingi\Cms\Entity\PageFragment[]
     */
    protected $fragments = null;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $editable = false;

    /**
     * @ORM\OneToMany(targetEntity="\Msingi\Cms\Entity\PageI18n",mappedBy="parent")
     * @var \Msingi\Cms\Entity\PageI18n[]
     */
    protected $i18n = null;

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

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Msingi\Cms\Entity\Page[] $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return \Msingi\Cms\Entity\Page[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param \Msingi\Cms\Entity\PageFragment[] $fragments
     */
    public function setFragments($fragments)
    {
        $this->fragments = $fragments;
    }

    /**
     * @return \Msingi\Cms\Entity\PageFragment[]
     */
    public function getFragments()
    {
        return $this->fragments;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @param \Msingi\Cms\Entity\PageI18n[] $i18n
     */
    public function setI18n($i18n)
    {
        $this->i18n = $i18n;
    }

    /**
     * @return \Msingi\Cms\Entity\PageI18n[]
     */
    public function getI18n()
    {
        return $this->i18n;
    }

    /**
     * @param boolean $editable
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;
    }

    /**
     * @return boolean
     */
    public function getEditable()
    {
        return $this->editable;
    }
}