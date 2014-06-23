<?php

namespace Ekologia\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Ekologia\MainBundle\Entity\TagRepository")
 */
class Tag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     * @ORM\ManyToMany(targetEntity="Ekologia\UserBundle\Entity\User", inversedBy="tags", cascade={"persist"})
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="Ekologia\CMSBundle\Entity\Page", inversedBy="tags", cascade={"persist"})
     */
    private $pages;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add users
     *
     * @param \Ekologia\UserBundle\Entity\User $users
     * @return Tag
     */
    public function addUser(\Ekologia\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Ekologia\UserBundle\Entity\User $users
     */
    public function removeUser(\Ekologia\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add page
     *
     * @param \Ekologia\CMSBundle\Entity\Page $page
     * @return Tag
     */
    public function addPage(\Ekologia\UserBundle\Entity\User $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \Ekologia\CMSBundle\Entity\Page $page
     */
    public function removePage(\Ekologia\CMSBundle\Entity\Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }
}
