<?php

namespace Ekologia\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="eko_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
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
     * @ORM\Column(name="addressStreet", type="string", length=255)
     */
    private $addressStreet;

    /**
     * @var integer
     *
     * @ORM\Column(name="addressZipCode", type="integer")
     * @Assert\Regex(pattern = "/^[0-9]{5}$/", message = "ekologia.user.user.address-zip-code.regex")
     */
    private $addressZipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="addressCity", type="string", length=255)
     */
    private $addressCity;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255)
     * @Assert\Url(message = "ekologia.user.user.avatar.url")
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(message = "ekologia.user.user.country.not-blank")
     */
    private $country;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $userType;
    
    /**
     * @ORM\OneToOne(targetEntity="Ekologia\UserBundle\Entity\PUser", cascade={"persist"})
     */
    private $puser;
    
    /**
     * @ORM\OneToOne(targetEntity="Ekologia\UserBundle\Entity\CUser", cascade={"persist"})
     */
    private $cuser;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set addressStreet
     *
     * @param string $addressStreet
     * @return User
     */
    public function setAddressStreet($addressStreet)
    {
        $this->addressStreet = $addressStreet;

        return $this;
    }

    /**
     * Get addressStreet
     *
     * @return string 
     */
    public function getAddressStreet()
    {
        return $this->addressStreet;
    }

    /**
     * Set addressZipCode
     *
     * @param integer $addressZipCode
     * @return User
     */
    public function setAddressZipCode($addressZipCode)
    {
        $this->addressZipCode = $addressZipCode;

        return $this;
    }

    /**
     * Get addressZipCode
     *
     * @return integer 
     */
    public function getAddressZipCode()
    {
        return $this->addressZipCode;
    }

    /**
     * Set addressCity
     *
     * @param string $addressCity
     * @return User
     */
    public function setAddressCity($addressCity)
    {
        $this->addressCity = $addressCity;

        return $this;
    }

    /**
     * Get addressCity
     *
     * @return string 
     */
    public function getAddressCity()
    {
        return $this->addressCity;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set user type
     *
     * @param string $userType
     * @return User
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get user type
     *
     * @return string 
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set puser
     *
     * @param \Ekologia\UserBundle\Entity\PUser $puser
     * @return User
     */
    public function setPuser(\Ekologia\UserBundle\Entity\PUser $puser = null)
    {
        $this->puser = $puser;

        return $this;
    }

    /**
     * Get puser
     *
     * @return \Ekologia\UserBundle\Entity\PUser 
     */
    public function getPuser()
    {
        return $this->puser;
    }

    /**
     * Set cuser
     *
     * @param \Ekologia\UserBundle\Entity\CUser $cuser
     * @return User
     */
    public function setCuser(\Ekologia\UserBundle\Entity\CUser $cuser = null)
    {
        $this->cuser = $cuser;

        return $this;
    }

    /**
     * Get cuser
     *
     * @return \Ekologia\UserBundle\Entity\CUser 
     */
    public function getCuser()
    {
        return $this->cuser;
    }
}
