<?php

namespace Ekologia\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="eko_menu")
 * @ORM\Entity
 */
class Menu {

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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     * 
     * @ORM\Column(name="route", type="string")
     */
    private $route;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Ekologia\MenuBundle\Entity\Menu", inversedBy="children")
     * @ORM\JoinColumn(nullable = true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Ekologia\MenuBundle\Entity\Menu", cascade={"persist"}, mappedBy="parent")
     */
    private $children;

    /**
     * 
     * @ORM\Column(name="parameters", type="json_array", nullable=true)
     */
    private $parameters;

    /**
     * @var string
     * 
     * @ORM\Column(name="language", type="string", length=255, nullable=true)
     */
    protected $language;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getParameters() {
        return $this->parameters;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setRoute($route) {
        $this->route = $route;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add children
     *
     * @param \Ekologia\MenuBundle\Entity\Menu $children
     * @return Menu
     */
    public function addChild(\Ekologia\MenuBundle\Entity\Menu $children) {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Ekologia\MenuBundle\Entity\Menu $children
     */
    public function removeChild(\Ekologia\MenuBundle\Entity\Menu $children) {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren() {
        return $this->children;
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

}
