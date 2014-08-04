<?php
namespace Ekologia\UserBundle\Entity;

use FOS\UserBundle\Doctrine\UserManager;

class UserRepository extends UserManager {
    public function findPossibleGroupAdministrators() {
        return $this->repository->findBy(array('userType' => 'puser'), array('username' => 'asc'));
    }
}
