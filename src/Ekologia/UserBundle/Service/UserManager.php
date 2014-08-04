<?php
namespace Ekologia\UserBundle\Service;

use FOS\UserBundle\Doctrine\UserManager as BaseManager;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager extends BaseManager {
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class) {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);
    }
    
    public function findPossibleGroupAdministrators() {
        return $this->repository->findPossibleGroupAdministrators();
    }
}
