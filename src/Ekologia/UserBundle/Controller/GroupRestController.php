<?php
namespace Ekologia\UserBundle\Controller;

use Ekologia\MainBundle\Controller\MasterController;
use Symfony\Component\HttpFoundation\Request;
use Ekologia\UserBundle\Entity\Group;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class GroupRestController extends MasterController {
    public function listAction($start=0, $limit=0) {
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
        if ($request->isMethod('POST')) {
            $form = $this->createForm('ekologia_user_grouptype', new Group());
            $form->handleRequest($request);
            if ($form->isValid()) {
                $group = $form->getData();
                $group->setCoordinator($group->getAdministrator());
                $em = $this->getDoctrine()->getManager();
                $em->persist($group);
                $em->flush();
                return $this->jsonResponse(array(
                    'valid' => true,
                    'group' => $this->getGroupRep()->groupToJson($group)
                ));
            } else {
                exit($form->getErrors(true));
                return $this->jsonResponse(array(
                    'valid' => false,
                    'error' => 'invalid-form',
                    'data' => $this->formErrorToJson($form->getErrors(true))
                ));
            }
        } else {
            return $this->jsonResponse(array(
                'valid' => false,
                'error' => $this->get('translator')->trans('not-post')
            ));
        }
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, $id) {
        if ($request->isMethod('POST')) {
            $group = $this->getGroupRep()->find($id);
            if ($group !== null) {
                $form = $this->createForm('ekologia_user_grouptype', $group);
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($group);
                    $em->flush();
                    return $this->jsonResponse(array(
                        'valid' => true,
                        'group' => $this->getGroupRep()->groupToJson($group)
                    ));
                } else {
                    return $this->jsonResponse(array(
                        'valid' => false,
                        'error' => 'invalid-form',
                        'data' => $this->formErrorToJson($form->getErrors(true))
                    ));
                }
            }
            else {
                return $this->jsonResponse(array(
                    'valid' => false,
                    'error' => $this->get('translator')->trans('group-not-exists')
                ));
            }
        } else {
            return $this->jsonResponse(array(
                'valid' => false,
                'error' => $this->get('translator')->trans('not-post')
            ));
        }
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id) {
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
                    return $this->jsonResponse(array(
                        'valid' => false,
                        'error' => 'invalid-form',
                        'data' => $this->formErrorToJson($form->getErrors(true))
                    ));
                }
            }
            else {
                return $this->jsonResponse(array(
                    'valid' => false,
                    'error' => $this->get('translator')->trans('ekologia.group.not-exists')
                ));
            }
        } else {
            return $this->jsonResponse(array(
                'valid' => false,
                'error' => $this->get('translator')->trans('not-post')
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
