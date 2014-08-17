<?php
namespace Ekologia\UserBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Ekologia\MainBundle\Controller\MasterController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Ekologia\UserBundle\Entity\Group;
use Ekologia\UserBundle\Entity\User;
use Ekologia\UserBundle\Entity\UserGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Translation\TranslatorInterface;


class GroupRestController extends MasterController {
    public function listAction($start = 0, $limit = 0) {
        return $this->jsonResponse(array(
                                       'groups' => $this->getGroupRep()->jsonList($start, $limit)
                                   ));
    }

    public function readAction($id) {
        return $this->jsonResponse(array(
                                       'group' => $this->getGroupRep()->jsonFind($id)
                                   ));
    }

    public function searchAction() {
        // TODO : how ?
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createAction(Request $request) {
        /* @var $group Group */
        /* @var $userGroup UserGroup */
        /* @var $form Form */
        /* @var $em ObjectManager */
        if ($request->isMethod('POST')) {
            $form = $this->createForm('ekologia_user_grouptype', new Group());
            $form->handleRequest($request);
            if ($form->isValid()) {
                $group = $form->getData();
                $group->setCoordinator($group->getAdministrator());
                $userGroup = new UserGroup();
                $userGroup->setGroup($group)
                          ->setUser($group->getAdministrator())
                          ->setCompose(true)
                          ->setRequestFromGroup(true)
                          ->setRequestDate(new \DateTime())
                          ->setValidationDate(new \DateTime())
                          ->setActive(true);
                $group->addUserGroup($userGroup);
                $em = $this->getDoctrine()->getManager();
                $em->persist($group);
                $em->flush();
                return $this->jsonResponse(array(
                                               'valid' => true,
                                               'group' => $this->getGroupRep()->groupToJson($group)
                                           ));
            } else {
                return $this->errorRequest('invalid-form', array('data' => $this->formErrorToJson($form->getErrors(true))));
            }
        } else {
            return $this->errorRequest('not-post');
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, $id) {
        /* @var $group Group */
        /* @var $userGroup UserGroup */
        /* @var $form Form */
        /* @var $em ObjectManager */
        if ($request->isMethod('POST')) {
            $group = $this->getGroupRep()->find($id);
            if ($group !== null) {
                $form = $this->createForm('ekologia_user_grouptype', $group);
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    if ($group->getUserGroups()->contains($group->getAdministrator()) === false) {
                        $userGroup = new UserGroup();
                        $userGroup->setGroup($group)
                                  ->setUser($group->getAdministrator())
                                  ->setCompose(true)
                                  ->setRequestFromGroup(true)
                                  ->setRequestDate(new \DateTime())
                                  ->setValidationDate(new \DateTime())
                                  ->setActive(true);
                        $group->addUserGroup($userGroup);
                    }
                    $em->flush();
                    return $this->jsonResponse(array(
                                                   'valid' => true,
                                                   'group' => $this->getGroupRep()->groupToJson($group)
                                               ));
                } else {
                    return $this->errorRequest('invalid-form', array('data' => $this->formErrorToJson($form->getErrors(true))));
                }
            } else {
                return $this->errorRequest('group-not-exists');
            }
        } else {
            return $this->errorRequest('not-post');
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id) {
        /* @var $group Group */
        /* @var $userGroup UserGroup */
        /* @var $form Form */
        /* @var $em ObjectManager */
        if ($request->isMethod('POST')) {
            $group = $this->getGroupRep()->find($id);
            if ($group !== null) {
                $form = $this->createForm('ekologia_main_deletetype');
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($group);
                    $em->flush();
                    return $this->jsonResponse(array(
                                                   'valid' => true
                                               ));
                } else {
                    return $this->errorRequest('invalid-form', array('data' => $this->formErrorToJson($form->getErrors(true))));
                }
            } else {
                return $this->errorRequest('ekologia.group.not-exists');
            }
        } else {
            return $this->errorRequest('not-post');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function requestFromUserAction(Request $request, $groupid, $type) {
        /* @var $user User */
        /* @var $group Group */
        /* @var $userGroup UserGroup */
        /* @var $em ObjectManager */
        /* @var $form Form */
        if ($request->isMethod('POST')) {
            $group = $this->getGroupRep()->find($groupid);
            $user = $this->getUser();
            if ($group === null || $user === null) {
                return $this->errorRequest($group === null ? 'eko.user.group.notfound' : 'eko.user.user.notfound');
            }
            if ($this->getUserGroupRep()->existsInGroup($groupid, $user->getId())) {
                return $this->errorRequest('eko.user.group.exist-in-group');
            } else {
                $form = $this->createForm('ekologia_usergroup_request');
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $compose = $user->getUserType() === 'puser' || $user->isAdherent() && $type === 'compose';
                    $userGroup = new UserGroup();
                    $userGroup->setGroup($group)
                              ->setUser($user)
                              ->setCompose($compose)
                              ->setRequestDate(new \DateTime())
                              ->setRequestFromGroup(false)
                              ->setActive(false)
                              ->setRole('member');
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($userGroup);
                    $em->flush();
                    return $this->jsonResponse(array('valid' => true));
                } else {
                    return $this->errorRequest('invalid-form', array('data' => $this->formErrorToJson($form->getErrors(true))));
                }
            }
        } else {
            return $this->errorRequest('not-post');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function requestFromGroupAction(Request $request, $groupid, $userid, $type) {
        /* @var $admin User */
        /* @var $user User */
        /* @var $group Group */
        /* @var $userGroup UserGroup */
        /* @var $em ObjectManager */
        if ($request->isMethod('POST')) {
            $group = $this->getGroupRep()->find($groupid);
            $user = $this->getUserRep()->find($userid);
            if ($group === null || $user === null) {
                return $this->errorRequest($group === null ? 'eko.user.group.notfound' : 'eko.user.user.notfound');
            }
            $admin = $this->getUser();
            if ($this->getGroupRep()->isAdminGroup($groupid, $admin->getId()) === false) {
                return $this->errorRequest('eko.user.group.not-admin');
            } else {
                if ($this->getUserGroupRep()->existsInGroup($groupid, $userid)) {
                    return $this->errorRequest('eko.user.group.exist-in-group');
                } else {
                    $form = $this->createForm('ekologia_usergroup_request');
                    $form->handleRequest($request);
                    if ($form->isValid()) {
                        $compose = $user->getUserType() === 'puser' || $user->isAdherent() && $type === 'compose';
                        $userGroup = new UserGroup();
                        $userGroup->setGroup($group)
                                  ->setUser($user)
                                  ->setCompose($compose)
                                  ->setRequestDate(new \DateTime())
                                  ->setRequestFromGroup(true)
                                  ->setActive(false)
                                  ->setRole('member');

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($userGroup);
                        $em->flush();
                        return $this->jsonResponse(array('valid' => true));
                    } else {
                        return $this->errorRequest('invalid-form', array('data' => $this->formErrorToJson($form->getErrors(true))));
                    }
                }
            }
        } else {
            return $this->errorRequest('not-post');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function acceptFromUserAction(Request $request, $groupid) {
        /* @var $user User */
        /* @var $group Group */
        /* @var $userGroup UserGroup */
        /* @var $em ObjectManager */
        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            $group = $this->getGroupRep()->find($groupid);
            if ($group === null || $user === null) {
                return $this->errorRequest($group === null ? 'eko.user.group.notfound' : 'eko.user.user.notfound');
            }
            $userGroup = $this->getGroupRep()->findBy(array('group' => $groupid, 'user' => $user->getId()));
            if ($userGroup === null) {
                return $this->errorRequest('eko.user.usergroup.notfound');
            } elseif ($userGroup->getActive()) {
                return $this->errorRequest('eko.user.usergroup.alreadyactive');
            } elseif ($userGroup->getRequestFromGroup() === false) {
                return $this->errorRequest('eko.user.usergroup.requestedbyuser');
            } else {
                $form = $this->createForm('ekologia_usergroup_request');
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $userGroup->setActive(true)->setValidationDate(new \DateTime());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($userGroup);
                    $em->flush();
                    return $this->jsonResponse(array('valid' => true));
                } else {
                    return $this->errorRequest('invalid-form', array('data' => $this->formErrorToJson($form->getErrors(true))));
                }
            }
        } else {
            return $this->errorRequest('not-post');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function acceptFromGroupAction(Request $request, $groupid, $userid) {
        /* @var $admin User */
        /* @var $user User */
        /* @var $group Group */
        /* @var $userGroup UserGroup */
        /* @var $em ObjectManager */
        if ($request->isMethod('POST')) {
            $group = $this->getGroupRep()->find($groupid);
            $user = $this->getUserRep()->find($userid);
            if ($group === null || $user === null) {
                return $this->errorRequest($group === null ? 'group-not-found' : 'user-not-found');
            }
            $admin = $this->getUser();
            if ($this->getGroupRep()->isAdminGroup($groupid, $admin->getId()) === false) {
                return $this->errorRequest('eko.user.group.not-admin');
            } else {
                $userGroup = $this->getUserGroupRep()->findOneBy(array('group' => $groupid, 'user' => $user->getId()));
                if ($userGroup === null) {
                    return $this->errorRequest('eko.user.usergroup.notfound');
                } elseif ($userGroup->getActive()) {
                    return $this->errorRequest('eko.user.usergroup.alreadyactive');
                } elseif ($userGroup->getRequestFromGroup()) {
                    return $this->errorRequest('eko.user.usergroup.requestedbyuser');
                } else {
                    $form = $this->createForm('ekologia_usergroup_request');
                    $form->handleRequest($request);
                    if ($form->isValid()) {
                        $userGroup->setActive(true)->setValidationDate(new \DateTime());
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($userGroup);
                        $em->flush();
                        return $this->jsonResponse(array('valid' => true));
                    } else {
                        return $this->errorRequest('invalid-form', array('data' => $this->formErrorToJson($form->getErrors(true))));
                    }
                }
            }
        } else {
            return $this->errorRequest('not-post');
        }
    }

    /**
     * @param $message
     * @param array $additionalData
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function errorRequest($message, array $additionalData = array()) {
        /* @var $translator TranslatorInterface */
        $translator = $this->get('translator');
        return $this->jsonResponse(array_merge(array(
                                                   'valid' => 'false',
                                                   'error' => $translator->trans($message)
                                               ), $additionalData));
    }

    /**
     * @return \Ekologia\UserBundle\Entity\GroupRepository
     */
    private function getGroupRep() {
        return $this->getDoctrine()->getRepository('EkologiaUserBundle:Group');
    }

    /**
     * @return \Ekologia\UserBundle\Entity\UserRepository
     */
    private function getUserRep() {
        return $this->get('ekologia_user.user_repository');
    }

    /**
     * @return \Ekologia\UserBundle\Entity\UserGroupRepository
     */
    private function getUserGroupRep() {
        return $this->getDoctrine()->getRepository('EkologiaUserBundle:UserGroup');
    }
}
