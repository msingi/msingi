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

}