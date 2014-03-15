<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PageI18n
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cms_pages_i18n", indexes={
 * @ORM\Index(columns={"parent_id", "language"}),
 * })
 */
class PageI18n
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
     * @ORM\Column(type="string", length=2)
     * @var string
     */
    protected $language = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $title = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $keywords = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $description = '';
}