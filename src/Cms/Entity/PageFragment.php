<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PageFragment
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\PageFragments")
 * @ORM\Table(name="cms_page_fragments", indexes={
 * @ORM\Index(columns={"name"})
 * })
 */
class PageFragment
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
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \Msingi\Cms\Entity\Page
     */
    protected $page = null;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\OneToMany(targetEntity="Msingi\Cms\Entity\PageFragmentI18n",mappedBy="parent")
     * @var \Msingi\Cms\Entity\PageFragmentI18n
     */
    protected $i18n = null;

    /**
     * @param \Msingi\Cms\Entity\PageFragmentI18n $i18n
     */
    public function setI18n($i18n)
    {
        $this->i18n = $i18n;
    }

    /**
     * @return \Msingi\Cms\Entity\PageFragmentI18n
     */
    public function getI18n()
    {
        return $this->i18n;
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
     * @param \Msingi\Cms\Entity\Page $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return \Msingi\Cms\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

}