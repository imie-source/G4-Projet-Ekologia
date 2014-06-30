<?php

namespace Ekologia\CMSBundle\Controller;

use Ekologia\ArticleBundle\Controller\ArticleController;
use Symfony\Component\HttpFoundation\Request;
use Ekologia\CMSBundle\Entity\Page;

/**
 * Actions for Page manipulation
 */
class PageController extends ArticleController {
    /**
     * Read a page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request The current request
     * @param string $canonical The canonical name of the page
     * @return \Symfony\Component\HttpFoundation\Response The HTTP response
     */
    public function readAction(Request $request, $canonical) {
        return $this->read($request, $canonical,
            function(Request $request, $article){ // whenOk
                return $this->render('EkologiaCMSBundle:Page:read.html.twig', array('article' => $article));
            }, function(Request $request, $article){ // when forbidden
                return $this->redirectResponse('homepage');
            }, function(Request $request, $canonical){ // when not exists
                return $this->redirectResponse('homepage');
            }
        );
    }
    
    /**
     * All steps to create a new page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request The current request
     * @return \Symfony\Component\HttpFoundation\Response The HTTP response
     */
    public function createAction(Request $request) {
        return $this->create($request,
            $this->showFormCreate(), // whenShow
            function(Request $request, Page $page){ // whenOk
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();
                return $this->redirectResponse('ekologia_cms_page_read', array('canonical' => $page->getCanonical()));
            },
            $this->showFormCreate(), // whenBadRequest
            function(Request $request){ // whenForbidden
                return $this->redirectResponse('homepage');
            }
        );
    }
    
    /**
     * All steps to update an existing page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request The current request
     * @param string $canonical The canonical name of the page
     * @return \Symfony\Component\HttpFoundation\Response The HTTP response
     */
    public function updateAction(Request $request, $canonical) {
        return $this->update($request, $canonical,
            $this->showFormEdit(), // whenShow
            function(Request $request, Page $page){ // whenOk
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();
                return $this->redirectResponse('ekologia_cms_page_read', array('canonical' => $page->getCanonical()));
            },
            $this->showFormEdit(), // whenBadRequest
            function(Request $request, Page $page){ // whenForbidden
                return $this->redirectResponse('homepage');
            }, function(Request $request, $canonical){ // When not exists
                return $this->redirectResponse('homepage');
            }
        );
    }
    
    /**
     * All steps to remove an existing page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request The current request
     * @param string $canonical The canonical name of the page
     * @return \Symfony\Component\HttpFoundation\Response The HTTP response
     */
    public function removeAction(Request $request, $canonical) {
        return $this->remove($request, $canonical,
            function(Request $request, $form){ // whenShow
                return $this->render('EkologiaCMSBundle:Page:remove.html.twig', array('form' => $form));
            }, function(Request $request, Page $page){ // whenOk
                $em = $this->getDoctrine()->getManager();
                $em->remove($page);
                $em->flush();
                return $this->redirectResponse('homepage');
            }, function(Request $request, $form){ // whenBadRequest
                return $this->render('EkologiaCMSBundle:Page:remove.html.twig', array('form' => $form));
            }, function(Request $request, Page $page){ // whenForbidden
                return $this->redirectResponse('homepage');
            }, function(Request $request, $canonical){ // When not exists
                return $this->redirectResponse('homepage');
            }
        );
    }
    
    private function showForm(Request $request, $form, $url) {
        return $this->render('EkologiaCMSBundle:Page:form.html.twig', array('form' => $form->createView(), 'formAction' => $url));
    }
    
    private function showFormCreate() {
        return function(Request $request, $form) {
            return $this->showForm($request, $form,
                                   $this->generateUrl('ekologia_cms_page_create'));
        };
    }
    
    private function showFormEdit() {
        return function(Request $request, $form) {
            return $this->showForm($request, $form,
                                   $this->generateUrl('ekologia_cms_page_update',
                                                      array('canonical' => $request->get('canonical'))));
        };
    }
    
    /**
     * Checks if the current user is has the ROLE_READER
     * 
     * @return boolean
     */
    private function isRoleReader() {
        return $this->get('security.context')->isGranted('ROLE_READER');
    }
    
    /**
     * Checks if the current user is has the ROLE_WRITER
     * 
     * @return boolean
     */
    private function isRoleWriter() {
        return $this->get('security.context')->isGranted('ROLE_WRITER');
    }
    
    /** {@inheritDoc} */
    protected function canCreate(\Symfony\Component\HttpFoundation\Request $request) {
        return $this->isRoleWriter();
    }

    /** {@inheritDoc} */
    protected function canRead(\Symfony\Component\HttpFoundation\Request $request, $element) {
        /* @var $element \Ekologia\CMSBundle\Entity\Page */
        if ($this->isRoleReader()) {
            if ($request->get('version') !== 'last' && !$element->hasActiveVersion()) {
                return false;
            } else {
                return true;
            }
        } else {
            return $element->getVisibility() === 'public' && $element->hasActiveVersion();
        }
    }

    /** {@inheritDoc} */
    protected function canRemove(\Symfony\Component\HttpFoundation\Request $request, $element) {
        /* @var $element \Ekologia\CMSBundle\Entity\Page */
        return $this->isRoleWriter() && $element->getDeletable();
    }

    /** {@inheritDoc} */
    protected function canUpdate(\Symfony\Component\HttpFoundation\Request $request, $element) {
        /* @var $element \Ekologia\CMSBundle\Entity\Page */
        return $this->isRoleWriter();
    }

    /** {@inheritDoc} */
    protected function getArticleClassName() {
        return '\Ekologia\CMSBundle\Entity\Page';
    }

    /** {@inheritDoc} */
    protected function getArticleDeleteFormType() {
        // TODO
    }

    /** {@inheritDoc} */
    protected function getArticleFormType() {
        return 'ekologia_cms_page';
    }

    /** {@inheritDoc} */
    protected function getArticleRepositoryName() {
        return 'EkologiaCMSBundle:Page';
    }

    /** {@inheritDoc} */
    protected function getVersionClassName() {
        
    }
    
    /** {@inheritDoc} */
    protected function getParentList(Request $request) {
        $list = $this->getDoctrine()
                     ->getRepository($this->getArticleRepositoryName())
                     ->findAll();
        $result = array();
        foreach ($list as $page) {
            $result[$page->getId()] = $page->getCurrentVersion(false)->getTitle();
        }
        return $result;
    }
}
