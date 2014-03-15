<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BackendUser
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\BackendUsers")
 * @ORM\Table(name="cms_backend_users", indexes={
 * @ORM\Index(columns={"username"})
 * })
 */
class BackendUser
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
    protected $username = '';

    /**
     * @ORM\Column(type="string",length=40)
     * @var string
     */
    protected $password = '';

    /**
     * @ORM\Column(type="string",length=40)
     * @var string
     */
    protected $password_salt = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $email = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected $role = '';

    /**
     * @var \DateTime
     */
    protected $dt_created = null;
}