<?php
namespace Ekologia\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository {
    public function jsonList($offset, $limit) {
        $qb = $this->createQueryBuilder('g')->orderBy('g.name', 'asc');
        if ($offset > 0) {
            $qb->setFirstResult($offset);
        }
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }
        $groups = $qb->getQuery()->getResult();
        return $this->groupsToJson($groups);
    }
    
    public function jsonFind($id) {
        $group = $this->find($id);
        return $this->groupToJson($group);
    }
    
    public function groupsToJson($groups) {
        $result = array();
        foreach ($groups as $group) {
            $result[] = $this->groupToJson($group);
        }
        return $result;
    }
    
    public function groupToJson($group) {
        if ($group === null) {
            return null;
        } else {
            return array(
                'id' => $group->getId(),
                'name' => $group->getName(),
                'description' => $group->getDescription() !== null ? $group->getDescription() : '',
                'coordinator' => $group->getCoordinator(),
                'administrator' => array(
                    'id' => $group->getAdministrator()->getId(),
                    'name' => $group->getAdministrator()->getUsername(),
                ),
                'compose' => $this->usersToJson($group->getUserCompose()),
                'participate' => $this->usersToJson($group->getUserParticipate())
            );
        }
    }
    
    private function usersToJson($users) {
        $result = array();
        foreach ($users as $user) {
            $result[] = $this->groupToJson($user);
        }
        return $result;
    }
    
    private function userToJson($user) {
        if ($user === null) {
            return null;
        } else {
            return array(
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'usertype' => $user->getUserType(),
                'avatar' => $user->getAvatar()
            );
        }
    }
}
