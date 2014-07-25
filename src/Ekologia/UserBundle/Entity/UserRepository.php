<?php
namespace Ekologia\UserBundle\Entity;

use FOS\UserBundle\Doctrine\UserManager;

class UserRepository extends UserManager {
    public function findPossibleGroupAdministrators() {
        return $this->findBy(array('userType' => 'puser'), array('username' => 'asc'));
    }
}
