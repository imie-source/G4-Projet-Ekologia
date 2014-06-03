<?php

namespace Ekologia\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentaires
 *
 * @ORM\Table(name="eko_user")
 * @ORM\Entity
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
     * @ORM\Column(name="text", type="string", length=255, nullable=true) 
     */
    private $text;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\ArticleBundle\Entity\Article", cascade={"persist"}, inversedBy="commentaires")
     */
    private $article;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\UserBundle\Entity\User", cascade={"persist"}, inversedBy="commentaires")
     */
    private $user;
    
    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
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

    public function setText($text) {
        $this->text = $text;
    }

    public function setArticle($article) {
        $this->article = $article;
    }

    public function setUser($user) {
        $this->user = $user;
    }


}
