<?php
namespace Ekologia\CMSBundle\Controller;

use Ekologia\MainBundle\Controller\MasterController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Action for page manipulation, use in page list.
 */
class ListController extends MasterController {
    /**
     * @Security("has_role('ROLE_WRITER')")
     */
    public function listAction(Request $request) {
        $pages = $this->getDoctrine()
                      ->getRepository('EkologiaCMSBundle:Page')
                      ->findBy(array('parent' => null, 'language' => $request->getLocale()));
        return $this->render('EkologiaCMSBundle:List:list.html.twig', array('pages' => $pages));
    }
}
