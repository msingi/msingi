<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MenuI18n
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\MenusI18n")
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
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
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
