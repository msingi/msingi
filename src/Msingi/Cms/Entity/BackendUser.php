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

    /**
     * @param \DateTime $dt_created
     */
    public function setDtCreated($dt_created)
    {
        $this->dt_created = $dt_created;
    }

    /**
     * @return \DateTime
     */
    public function getDtCreated()
    {
        return $this->dt_created;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password_salt
     */
    public function setPasswordSalt($password_salt)
    {
        $this->password_salt = $password_salt;
    }

    /**
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->password_salt;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


}
