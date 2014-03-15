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
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Msingi\Cms\Entity\Menu")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \Msingi\Cms\Entity\Page
     */
    protected $parent = null;

    /**
     * @ORM\Column(type="string", length=30)
     * @var string
     */
    protected $menu = '';

    /**
     * @ORM\Column(type="integer")
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
}