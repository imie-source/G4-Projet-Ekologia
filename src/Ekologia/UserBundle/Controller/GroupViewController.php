<?php

namespace Ekologia\UserBundle\Controller;

use Ekologia\MainBundle\Controller\MasterController;
use Ekologia\UserBundle\Entity\Group;
use Ekologia\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GroupViewController extends MasterController {
    public function searchAction() {
        return $this->render('EkologiaUserBundle:Group:search.html.twig');
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function myGroupsAction() {
        /* @var $user User */
        $user = $this->getUser();
        return $this->render('EkologiaUserBundle:Group:mygroups.html.twig', array(
            'usergroups'  => $user->getUserGroups(),
            'formRequest' => $this->createForm('ekologia_usergroup_request')->createView()
        ));
    }

    public function viewAction($id) {
        /* @var $group Group */
        $group = $this->getGroupRep()->find($id);
        if ($group === null) {
            return $this->render('EkologiaUserBundle:Group:not-found.html.twig', array(
                'id' => $id
            ));
        } else {
            return $this->render('EkologiaUserBundle:Group:view.html.twig', array(
                'group'       => $group,
                'formRequest' => $this->createForm('ekologia_usergroup_request')->createView()
            ));
        }
    }

    public function usersAction($id) {
        /* @var $group Group */
        $group = $this->getGroupRep()->find($id);
        if ($group === null) {
            return $this->render('EkologiaUserBundle:Group:not-found.html.twig', array(
                'id' => $id
            ));
        } else {
            return $this->render('EkologiaUserBundle:Group:users.html.twig', array(
                'group'       => $group,
                'isUserAdmin' => $this->getUser() !== null && $this->getGroupRep()->isAdminGroup($group->getId(), $this->getUser()->getId()),
                'formRequest' => $this->createForm('ekologia_usergroup_request')->createView()
            ));
        }
    }

    /**
     * @return \Ekologia\UserBundle\Entity\GroupRepository
     */
    private function getGroupRep() {
        return $this->getDoctrine()->getRepository('EkologiaUserBundle:Group');
    }
}
