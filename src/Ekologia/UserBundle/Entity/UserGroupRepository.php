<?php
/**
 * Created by PhpStorm.
 * User: siteko
 * Date: 16/08/14
 * Time: 11:58
 */

namespace Ekologia\UserBundle\Entity;


use Doctrine\ORM\EntityRepository;

class UserGroupRepository extends EntityRepository {
    public function existsInGroup($groupid, $userid) {
        return $this->createQueryBuilder('ug')
                    ->select('count(ug)')
                    ->where('ug.group = :group')
                    ->setParameter('group', $groupid)
                    ->andWhere('ug.user = :user')
                    ->setParameter('user', $userid)
                    ->getQuery()
                    ->getSingleScalarResult() == 1;
    }
}