<?php

namespace Ekologia\MenuBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Provide methods for querying Menu
 */
class MenuRepository extends EntityRepository {
    /**
     * Finds the main menu in terms of given name.
     * 
     * @param string $name The name menu to find
     */
    public function findParent($name, $language) {
        return $this->findOneBy(array('parent' => null, 'name' => $name, 'language' => $language));
    }
}
