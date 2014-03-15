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
}