<?php

namespace Ekologia\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Article
 * @ORM\MappedSuperclass
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
     * @ORM\Column(name="language", type="string", length=255)
     */
    protected $language;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="canonical", type="string", length=255)
     */
    protected $canonical;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="deletable", type="boolean")
     * @Assert\NotBlank(message = "ekologia.article.article.deletable.not-blank")
     */
    protected $deletable;
    
    /**
     * Visibility of the article.
     * Can be 'public', 'protected' or 'private'
     * @var string
     * 
     * @ORM\Column(name="visibility", type="string", length=255)
     * @Assert\NotBlank(message = "ekologia.article.article.visibility.not-blank")
     */
    protected $visibility;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="dateCreation", type="date", length=255) 
     * @Assert\NotBlank(message = "ekologia.article.article.dateCreation.not-blank")
     */
    protected $dateCreation;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $tags;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $commentaires;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $versions;
    
    /**
     * @var \Ekologia\ArticleBundle\Entity\Article
     */
    protected $parent;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $children;
    
    /**
     * Version used in forms
     * 
     * @var type \Ekologia\ArticleBundle\Entity\Version
     */
    protected $version;
    
    
    public function __construct() {
        $this->dateCreation = new \DateTime();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->versions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get article id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set article language
     * @param string $language
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function setLanguage($language) {
        $this->language = $language;
        return $this;
    }

    /**
     * Get article language
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set the canonical name of the article.
     * The canonical can be used for URL.
     * @param string $canonical
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function setCanonical($canonical) {
        $this->canonical = $canonical;
        return $this;
    }

    /**
     * Get the canonical name of the article.
     * The canonical can be used for URL.
     * @return string
     */
    public function getCanonical() {
        return $this->canonical;
    }

    /**
     * Set if the object is deletable
     * @param boolean $deletable
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function setDeletable($deletable) {
        $this->deletable = $deletable;
        return $this;
    }
    
    /**
     * Get if the object is deletable
     * @return boolean
     */
    public function getDeletable() {
        return $this->deletable;
    }

    /**
     * Set the article visibility. It could be 'private', 'protected' or 'public'
     * @param string $visibility
     * @return \Ekologia\ArticleBundle\Entity\Article
     */
    public function setVisibility($visibility) {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * Get the article visibility
     * @return string
     */
    public function getVisibility() {
        return $this->visibility;
    }

    /**
     * Set the article creation date
     * @param \DateTime $dateCreation
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function setDateCreation(\DateTime $dateCreation) {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    /**
     * Get the article creation date
     * @return \DateTime
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }
    
    /**
     * Add a tag into the article
     * @param Tag $tag
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function addTag($tag) {
        $this->tags[] = $tag;
        return $this;
    }
    
    /**
     * Remove a tag from the article
     * @param string $tag
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function removeTag($tag) {
        $this->tags->removeElement($tag);
        return $this;
    }

    /**
     * Get article tags
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags() {
        return $this->tags;
    }
    
    /**
     * Add a comment in the article
     * @param \Ekologia\ArticleBundle\Entity\Commentaire $commentaire
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function addCommentaire(\Ekologia\ArticleBundle\Entity\Commentaire $commentaire) {
        $this->commentaires[] = $commentaire;
        $commentaire.setArticle($this);
        return $this;
    }
    
    /**
     * Remove a comment from the article
     * @param \Ekologia\ArticleBundle\Entity\Commentaire $commentaire
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function removeComentaire(\Ekologia\ArticleBundle\Entity\Commentaire $commentaire) {
        $this->commentaires->removeElement($commentaire);
        $commentaire->setArticle(null);
        return $this;
    }

    /**
     * Get comments from the article
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCommentaires() {
        return $this->commentaires;
    }
    
    /**
     * Add a version in the article
     * @param \Ekologia\ArticleBundle\Entity\Version $version
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function addVersion(\Ekologia\ArticleBundle\Entity\Version $version) {
        $this->versions[] = $version;
        $version.setArticle($this);
        return $this;
    }
    
    /**
     * Remove a version from the article
     * @param \Ekologia\ArticleBundle\Entity\Version $version
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function removeVersion(\Ekologia\ArticleBundle\Entity\Version $version) {
        $this->versions->removeElement($version);
        $version->setArticle(null);
        return $this;
    }

    /**
     * Get versions of the article
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getVersions() {
        return $this->versions;
    }
    
    /**
     * Set the article parent
     * @param \Ekologia\ArticleBundle\Entity\Article $parent
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function setParent(\Ekologia\ArticleBundle\Entity\Article $parent) {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * Get the article parent
     * @return \Ekologia\ArticleBundle\Entity\Article
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Add a children into the article
     * @param \Ekologia\ArticleBundle\Entity\Article $children
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function addChildren(\Ekologia\ArticleBundle\Entity\Article $children) {
        $this->children[] = $children;
        $children->setParent($this);
        return $this;
    }
    
    /**
     * Remove a children from the article
     * @param \Ekologia\ArticleBundle\Entity\Article $children
     * @return \Ekologia\ArticleBundle\Entity\Article This object
     */
    public function removeChildren(\Ekologia\ArticleBundle\Entity\Article $children) {
        $this->children->removeElement($children);
        $children->setParent(null);
        return $this;
    }
    
    /**
     * Return the children of the article
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildren() {
        return $this->children;
    }
    
    /**
     * Set the version in the article - use for forms
     * @param \Ekologia\ArticleBundle\Entity\Version $version
     * @return \Ekologia\ArticleBundle\Entity\Article
     */
    public function setVersion(\Ekologia\ArticleBundle\Entity\Version $version) {
        $this->version = $version;
        return $this;
    }
    
    /**
     * Get the version of the article - us for forms
     * @return \Ekologia\ArticleBundle\Entity\Version
     */
    public function getVersion() {
        return $this->version;
    }
    
    /**
     * Return the current version of the article
     * 
     * @param string $isActive If true, returns only the last active version
     */
    public function getCurrentVersion($isActive=true) {
        foreach ($this->getVersions() as $version) {
            if (!$isActive || $version->getActive()) {
                return $version;
            }
        }
        return null;
    }
}
