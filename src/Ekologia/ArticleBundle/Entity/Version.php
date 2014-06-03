<?php

namespace Ekologia\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Versions
 *
 * @ORM\Table(name="eko_user")
 * @ORM\Entity
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
     * @var date
     * 
     * @ORM\Column(name="dateVersion", type="date", length=255) 
     * @Assert\NotBlank(message = "ekologia.article.version.dateVersion.not-blank")

     */
    private $dateVersion;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="content", type="string", length=255, nullable=true)
     */
    private $content;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\ArticleBundle\Entity\Commentaire", cascade={"persist"}, inversedBy="versions")
     */
    private $article;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\ArticleBundle\Entity\Commentaire", cascade={"persist"}, inversedBy="versions")
     */
    private $user;
    
    public function getId() {
        return $this->id;
    }

    public function getDateVersion() {
        return $this->dateVersion;
    }

    public function getContent() {
        return $this->content;
    }

    public function getArticle() {
        return $this->article;
    }

    public function getUser() {
        return $this->user;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDateVersion(date $dateVersion) {
        $this->dateVersion = $dateVersion;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setArticle($article) {
        $this->article = $article;
    }

    public function setUser($user) {
        $this->user = $user;
    }


}
