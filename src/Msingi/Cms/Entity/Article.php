<?php

namespace Msingi\Cms\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Msingi\Cms\Entity\Enum\ArticleStatus;
use Msingi\Doctrine\EntityManagerAwareInterface;

/**
 * Class Article
 *
 * @package Msingi\Cms\Entity
 *
 * @ORM\Entity(repositoryClass="Msingi\Cms\Repository\Articles")
 * @ORM\Table(name="cms_articles", indexes={
 * @ORM\Index(columns={"status", "date"})
 * })
 */
class Article implements EntityManagerAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="article_status")
     * @var string
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $date;

    /**
     * @ORM\OneToMany(targetEntity="ArticleI18n",mappedBy="parent")
     * @var ArticleI18n[]
     */
    protected $i18n;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == ArticleStatus::STATUS_PUBLISHED;
    }

    /**
     * @param string $language
     * @return ArticleI18n
     */
    public function getI18n($language)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getEntityManager();

        $className = get_called_class() . 'I18n';

        $query = $em->createQuery('SELECT i18n FROM ' . $className . ' i18n WHERE i18n.parent = :parent AND i18n.language = :language');
        $query->setParameter('parent', $this->id);
        $query->setParameter('language', $language);

        $query->useQueryCache(true);
//        $query->useResultCache(false);

        $i18n = $query->getOneOrNullResult();
        if ($i18n == null) {
            $i18n = new $className();
            $i18n->setParent($this);
            $i18n->setLanguage($language);

            $this->i18n[] = $i18n;

            $em->persist($this);
            $em->persist($i18n);
            $em->flush();
        }

        return $i18n;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
