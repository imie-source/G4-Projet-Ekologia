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
            function(Request $request, $form){ // whenShow
                return $this->render('EkologiaCMSBundle:Page:form.html.twig', array('form' => $form, 'formAction' => 'ekologia_cms_page_create'));
            }, function(Request $request, Page $page){ // whenOk
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();
                return $this->redirectResponse('ekologia_cms_page_read', array('canonical' => $page->getCanonical()));
            }, function(Request $request, $form){ // whenBadRequest
                return $this->render('EkologiaCMSBundle:Page:form.html.twig', array('form' => $form, 'formAction' => 'ekologia_cms_page_create'));
            }, function(Request $request){ // whenForbidden
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
            function(Request $request, $form){ // whenShow
                return $this->render('EkologiaCMSBundle:Page:form.html.twig', array('form' => $form, 'formAction' => 'ekologia_cms_page_update'));
            }, function(Request $request, Page $page){ // whenOk
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();
                return $this->redirectResponse('ekologia_cms_page_read', array('canonical' => $page->getCanonical()));
            }, function(Request $request, $form){ // whenBadRequest
                return $this->render('EkologiaCMSBundle:Page:form.html.twig', array('form' => $form, 'formAction' => 'ekologia_cms_page_update'));
            }, function(Request $request, Page $page){ // whenForbidden
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
        if ($this->isRoleWriter()) {
            return in_array($element->getVisibility(), array('public', 'protected'));
        } else {
            return $element->getVisibility() === 'public';
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
        // TODO
    }

    /** {@inheritDoc} */
    protected function getArticleRepositoryName() {
        return 'EkologiaCMSBundle:Page';
    }

    /** {@inheritDoc} */
    protected function getVersionClassName() {
        
    }
}
