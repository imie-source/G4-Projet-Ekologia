<?php

namespace Ekologia\CMSBundle\Entity;

use Ekologia\ArticleBundle\Entity\Version as AbstractVersion;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="eko_pageversion")
 */
class Version extends AbstractVersion {
    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\CMSBundle\Entity\Page", inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $article;
    
    /**
     * @var string
     * @ORM\Column(name="css", type="text")
     */
    private $css;
    
    /**
     * @var string
     * @ORM\Column(name="js", type="text")
     */
    private $js;
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Set page css
     * 
     * @param string $css
     * @return \Ekologia\CMSBundle\Entity\Version This object
     */
    public function setCss($css) {
        $this->css = $css;
        return $this;
    }

    /**
     * Get page css
     * @return string
     */
    public function getCss() {
        return $this->css;
    }

    /**
     * Set page js
     * 
     * @param string $js
     * @return \Ekologia\CMSBundle\Entity\Version This object
     */
    public function setJs($js) {
        $this->js = $js;
        return $this;
    }

    /**
     * Get page js
     * @return string
     */
    public function getJs() {
        return $this->js;
    }
}
