<?php
namespace Ekologia\UserBundle\Controller;

use Ekologia\MainBundle\Controller\MasterController;


class GroupRestController extends MasterController {
    public function listAction($start=0, $limit=10) {
        return $this->jsonResponse(array(
            'groups' => $this->getDoctrine()->getRepository('EkologiaUserBundle:Group')->jsonList($start, $limit)
        ));
    }
    
    public function readAction($id) {
        return $this->jsonResponse(array(
            'group' => $this->getDoctrine()->getRepository('EkologiaUserBundle:Group')->jsonFind($id)
        ));
    }
    
    public function searchAction() {
        // TODO : how ?
    }
    
    public function createAction(Request $request) {
        if ($request->isMethod('POST')) {
            $form = $this->createForm('groupType', new Group());
            $form->handleRequest();
            if ($form->isValid()) {
                $group = $form->getData();
                $group->setCoordinator($group->getAdministrator());
                $em = $this->getDoctrine()->getManager();
                $em->persist($group);
                $em->flush();
                return $this->jsonResponse(array(
                    'valid' => true
                ));
            } else {
                return $this->jsonResponse(array(
                    'valid' => false,
                    'error' => 'invalid-form',
                    'data' => $form->getErrors(true)
                ));
            }
        } else {
            return $this->jsonResponse(array(
                'valid' => false,
                'error' => $this->get('translator')->trans('not-post')
            ));
        }
    }
    
    public function updateAction(Request $request, $id) {
        
    }
    
    public function deleteAction(Request $request, $id) {
        
    }
}
