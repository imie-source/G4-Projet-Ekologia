<?php

namespace Ekologia\CMSBundle\Entity;

use Ekologia\ArticleBundle\Entity\Article;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Ekologia\CMSBundle\Entity\PageRepository")
 * @ORM\Table(name="eko_page", uniqueConstraints={@ORM\UniqueConstraint(columns={"language", "canonical"})})
 */
class Page extends Article {
    /**
     * @ORM\OneToMany(targetEntity="Ekologia\CMSBundle\Entity\Version", cascade={"persist"}, mappedBy="article")
     */
    protected $versions;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\CMSBundle\Entity\Page", inversedBy="children")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Ekologia\CMSBundle\Entity\Page", mappedBy="parent")
     */
    protected $children;
    
    /**
     * @ORM\ManyToMany(targetEntity="Ekologia\MainBundle\Entity\Tag", cascade={"persist"}, mappedBy="articles")
     */
    protected $tags;
}
