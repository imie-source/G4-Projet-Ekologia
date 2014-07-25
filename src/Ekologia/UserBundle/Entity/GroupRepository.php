<?php
namespace Ekologia\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository {
    public function jsonList($offset, $limit) {
        $groups = $this->createQueryBuilder('g')
                       ->setFirstResult($offset)
                       ->setMaxResults($limit);
        return $this->groupsToJson($groups);
    }
    
    public function jsonFind($id) {
        $group = $this->find($id);
        return $this->groupToJson($group);
    }
    
    private function groupsToJson($groups) {
        $result = array();
        foreach ($groups as $group) {
            $result[] = $this->groupToJson($group);
        }
        return $result;
    }
    
    private function groupToJson($group) {
        if ($group === null) {
            return null;
        } else {
            return array(
                'id' => $group->getId(),
                'name' => $group->getName(),
                'coordinator' => $group->getCoordinator(),
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
