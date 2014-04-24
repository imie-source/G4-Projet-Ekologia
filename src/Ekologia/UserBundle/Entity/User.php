<?php

namespace Ekologia\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="eko_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message = "Ekologia.User.nom.notBlank")
     */
    protected $nom;
    
    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message = "Ekologia.User.prenom.notBlank")
     */
    protected $prenom;
    
    /**
     * @ORM\Column(type="date", length=32)
     * @Assert\NotBlank(message = "Ekologia.User.dateNaissance.notBlank")
     * @Assert\Date(message = "Ekologia.User.dateNaissance.DateType")
     */
    protected $dateNaissance;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $numeroEtVoie;
    
    /**
     * @ORM\Column(type="integer", length=5)
     * @Assert\Regex(pattern = "/^[0-9]{5}$/", message = "Ekologia.User.CodePostal.regex")
     */
    protected $codePostale;
    
    /**min
     * @ORM\Column(type="string", length=100)
     */
    protected $ville;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $pays;
      
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Url(message = "Ekologia.User.Photo.URL")
     */
    protected $photo;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getId() {
        return $this->id;
    }
        
    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getDateNaissance() {
        return $this->dateNaissance;
    }

    public function getNumeroEtVoie() {
        return $this->numeroEtVoie;
    }

    public function getCodePostale() {
        return $this->codePostale;
    }

    public function getVille() {
        return $this->ville;
    }

    public function getPays() {
        return $this->pays;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setDateNaissance($dateNaissance) {
        $this->dateNaissance = $dateNaissance;
    }

    public function setNumeroEtVoie($numeroEtVoie) {
        $this->numeroEtVoie = $numeroEtVoie;
    }

    public function setCodePostale($codePostale) {
        $this->codePostale = $codePostale;
    }

    public function setVille($ville) {
        $this->ville = $ville;
    }

    public function setPays($pays) {
        $this->pays = $pays;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }


}