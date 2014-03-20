<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MenuI18n
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cms_menu_i18n", indexes={
 * @ORM\Index(columns={"parent_id", "language"}),
 * })
 */
class MenuI18n
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
    protected $label = '';
}