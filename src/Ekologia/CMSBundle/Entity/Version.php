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
}
