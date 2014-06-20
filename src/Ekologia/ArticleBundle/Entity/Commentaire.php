<?php

namespace Ekologia\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentaires
 * @ORM\MappedSuperclass
 */
class Commentaire {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="text", type="text")
     */
    private $text;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var \Ekologia\ArticleBundle\Entity\Article
     */
    private $article;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\UserBundle\Entity\User", cascade={"persist"})
     */
    private $user;
    
    
    public function __construct() {
        $this->date = new \DateTime();
    }
    
    /**
     * Get the comment id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set the comment title
     * @param type $title
     * @return \Ekologia\ArticleBundle\Entity\Commentaire This object
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get the comment title
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set the comment text
     * @param type $text
     * @return \Ekologia\ArticleBundle\Entity\Commentaire This object
     */
    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    /**
     * Get the comment text
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Set the comment date
     * @param \DateTime $date
     * @return \Ekologia\ArticleBundle\Entity\Commentaire This object
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Get the comment date
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set the comment article
     * @param \Ekologia\ArticleBundle\Entity\Article $article
     * @return \Ekologia\ArticleBundle\Entity\Commentaire This object
     */
    public function setArticle($article) {
        $this->article = $article;
        return $this;
    }

    /**
     * Get the comment article
     * @return \Ekologia\ArticleBundle\Entity\Article
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Set the author
     * @param \Ekologia\UserBundle\Entity\User $user
     * @return \Ekologia\ArticleBundle\Entity\Commentaire This object
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
}
