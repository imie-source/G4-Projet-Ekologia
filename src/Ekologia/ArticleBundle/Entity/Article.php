<?php

namespace Ekologia\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Article
 *
 * @ORM\Entity
 */
class Article {
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
     * @ORM\Column(name="language", type="string", length=255, nullable=true)
     */
    private $language;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="canonical", type="string", length=255, nullable=true) 
     */
    private $canonical;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="deletable", type="boolean")
     * @Assert\NotBlank(message = "ekologia.article.article.deletable.not-blank")  
     */
    private $deletable;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="visibility", type="string", length=255)
     * @Assert\NotBlank(message = "ekologia.article.article.visibility.not-blank") 
     */
    private $visibility;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="dateCreation", type="date", length=255) 
     * @Assert\NotBlank(message = "ekologia.article.article.dateCreation.not-blank")
     */
    private $dateCreation;
    
    /**
     * @ORM\ManyToMany(targetEntity="Ekologia\MainBundle\Entity\Tag", cascade={"persist"}, mappedBy="articles")
     */
    private $tags;
    
    /**
     * @ORM\OneToMany(targetEntity="Ekologia\ArticleBundle\Entity\Commentaire", cascade={"persist"}, mappedBy="article")
     */
    private $commentaires;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Ekologia\ArticleBundle\Entity\Version", cascade={"persist"}, mappedBy="article")
     */
    private $versions;
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getCanonical() {
        return $this->canonical;
    }

    public function getDeletable() {
        return $this->deletable;
    }

    public function getVisibility() {
        return $this->visibility;
    }

    public function getDateCreation() {
        return $this->dateCreation;
    }

    public function getTags() {
        return $this->tags;
    }

    public function getCommentaires() {
        return $this->commentaires;
    }

    public function getVersions() {
        return $this->versions;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function setCanonical($canonical) {
        $this->canonical = $canonical;
    }

    public function setDeletable($deletable) {
        $this->deletable = $deletable;
    }

    public function setVisibility($visibility) {
        $this->visibility = $visibility;
    }

    public function setDateCreation(date $dateCreation) {
        $this->dateCreation = $dateCreation;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    public function setCommentaires($commentaires) {
        $this->commentaires = $commentaires;
    }

    public function setVersions($versions) {
        $this->versions = $versions;
    }


}
