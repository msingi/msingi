<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PageTemplate
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\PageTemplates")
 * @ORM\Table(name="cms_page_templates", indexes={
 * @ORM\Index(columns={"name"})
 * })
 */
class PageTemplate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $label = '';

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $fragments = '';

    /**
     * @param string $fragments
     */
    public function setFragments($fragments)
    {
        $this->fragments = $fragments;
    }

    /**
     * @return string
     */
    public function getFragments()
    {
        return $this->fragments;
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


}
