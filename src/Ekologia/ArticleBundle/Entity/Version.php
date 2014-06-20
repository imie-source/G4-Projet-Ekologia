<?php

namespace Ekologia\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Versions
 * @ORM\MappedSuperclass
 */
class Version {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateVersion", type="date", length=255) 
     * @Assert\NotBlank(message = "ekologia.article.version.dateVersion.not-blank")
     */
    private $dateVersion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    private $article;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\UserBundle\Entity\User")
     */
    private $user;
    
    /**
     * Get the version id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    public function __construct() {
        $this->dateVersion = new \DateTime();
    }

    /**
     * Set the version date
     * @param \DateTime $dateVersion
     * @return \Ekologia\ArticleBundle\Entity\Version This object
     */
    public function setDateVersion(\DateTime $dateVersion) {
        $this->dateVersion = $dateVersion;
        return $this;
    }

    /**
     * Get the version date
     * @return \DateTime
     */
    public function getDateVersion() {
        return $this->dateVersion;
    }

    /**
     * Set article title
     * 
     * @param string $title
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Get article title
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set the content
     * @param string $content
     * @return \Ekologia\ArticleBundle\Entity\Version This object
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the content
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set the article
     * @param \Ekologia\ArticleBundle\Entity\Article $article
     * @return \Ekologia\ArticleBundle\Entity\Version This object
     */
    public function setArticle($article) {
        $this->article = $article;
        return $this;
    }

    /**
     * Get the article
     * @return \Ekologia\ArticleBundle\Entity\Article
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Set the author
     * @param \Ekologia\UserBundle\Entity\User $user
     * @return \Ekologia\ArticleBundle\Entity\Version This object
     */
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the author
     * @return \Ekologia\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set active
     * @param boolean $active
     * @return \Ekologia\ArticleBundle\Entity\Version This object
     */
    public function setActive($active) {
        $this->active = $active;
        return $this;
    }

    /**
     * Get active
     * @return boolean
     */
    public function getActive() {
        return $this->active;
    }
}
