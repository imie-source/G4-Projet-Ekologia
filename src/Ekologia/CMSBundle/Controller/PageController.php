<?php

namespace Ekologia\CMSBundle\Controller;

use Ekologia\ArticleBundle\Controller\ArticleController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Actions for Page manipulation
 */
class PageController extends ArticleController {
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
