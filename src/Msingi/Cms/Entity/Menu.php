<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Menu
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\Menus")
 * @ORM\Table(name="cms_menu", indexes={
 * @ORM\Index(columns={"menu"}),
 * })
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Msingi\Cms\Entity\Menu")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \Msingi\Cms\Entity\Menu
     */
    protected $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="Msingi\Cms\Entity\Menu", mappedBy="parent")
     * @var \Msingi\Cms\Entity\Menu[]
     */
    protected $children = null;

    /**
     * @ORM\Column(type="string", length=30)
     * @var string
     */
    protected $menu = '';

    /**
     * @ORM\Column(name="`order`", type="integer")
     * @var int
     */
    protected $order = 0;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $route = '';

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $params = '';

    /**
     * @ORM\OneToMany(targetEntity="\Msingi\Cms\Entity\MenuI18n",mappedBy="parent")
     * @var \Msingi\Cms\Entity\MenuI18n[]
     */
    protected $i18n = null;

    /**
     * @param \Msingi\Cms\Entity\MenuI18n[] $i18n
     */
    public function setI18n($i18n)
    {
        $this->i18n = $i18n;
    }

    /**
     * @return \Msingi\Cms\Entity\MenuI18n[]
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
     * @param string $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return string
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param \Msingi\Cms\Entity\Menu $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \Msingi\Cms\Entity\Menu
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param \Msingi\Cms\Entity\Menu[] $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return \Msingi\Cms\Entity\Menu[]
     */
    public function getChildren()
    {
        return $this->children;
    }

}