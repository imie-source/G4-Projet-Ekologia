<?php

namespace Ekologia\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserGroup
 *
 * @ORM\Table(name="eko_usergroup")
 * @ORM\Entity
 */
class UserGroup extends AbstractGroupLink
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="compose", type="boolean")
     */
    private $compose;
    
    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="Ekologia\UserBundle\Entity\User", inversedBy="userGroup")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * @var Group
     * 
     * @ORM\ManyToOne(targetEntity="Ekologia\UserBundle\Entity\Group", inversedBy="userGroup")
     * @ORM\JoinColumn(nullable=false)
     */
    private $group;


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
     * Set compose
     *
     * @param boolean $compose
     * @return UserGroup
     */
    public function setCompose($compose)
    {
        $this->compose = $compose;

        return $this;
    }

    /**
     * Get compose
     *
     * @return boolean 
     */
    public function getCompose()
    {
        return $this->compose;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return UserGroup
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param Group $group
     * @return UserGroup
     */
    public function setGroup(Group $group = null)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Get group
     *
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}
