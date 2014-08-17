<?php

namespace Ekologia\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractGroupLink
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractGroupLink
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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var boolean
     *
     * @ORM\Column(name="requestFromGroup", type="boolean")
     */
    private $requestFromGroup;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="requestDate", type="datetime")
     */
    private $requestDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validationDate", type="datetime", nullable=true)
     */
    private $validationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=true)
     */
    private $role;


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
     * Set active
     *
     * @param boolean $active
     * @return AbstractGroupLink
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set requestFromGroup
     *
     * @param boolean $requestFromGroup
     * @return AbstractGroupLink
     */
    public function setRequestFromGroup($requestFromGroup)
    {
        $this->requestFromGroup = $requestFromGroup;

        return $this;
    }

    /**
     * Get requestFromGroup
     *
     * @return boolean 
     */
    public function getRequestFromGroup()
    {
        return $this->requestFromGroup;
    }

    /**
     * Set requestDate
     *
     * @param \DateTime $requestDate
     * @return AbstractGroupLink
     */
    public function setRequestDate($requestDate)
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    /**
     * Get requestDate
     *
     * @return \DateTime 
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }

    /**
     * Set validationDate
     *
     * @param \DateTime $validationDate
     * @return AbstractGroupLink
     */
    public function setValidationDate($validationDate)
    {
        $this->validationDate = $validationDate;

        return $this;
    }

    /**
     * Get validationDate
     *
     * @return \DateTime 
     */
    public function getValidationDate()
    {
        return $this->validationDate;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return AbstractGroupLink
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }
}
