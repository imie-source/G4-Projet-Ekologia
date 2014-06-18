<?php

namespace Ekologia\ArticleBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines methods to querying Article
 */
class ArticleRepository extends EntityRepository {
    /**
     * Find all articles that have any parent
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request The current request
     * @return \Ekologia\ArticleBundle\Entity\Article[] The list of articles
     */
    public function findParents(Request $request) {
        return $this->findBy(array('parent' => null));
    }
}
