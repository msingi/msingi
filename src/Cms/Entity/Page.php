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


}