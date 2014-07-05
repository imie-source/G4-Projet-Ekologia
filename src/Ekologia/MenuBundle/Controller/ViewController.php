<?php
namespace Ekologia\MenuBundle\Controller;

use Ekologia\MainBundle\Controller\MasterController;
use Symfony\Component\HttpFoundation\Request;

/**
 * This controller provide some methods to show menu in this site
 */
class ViewController extends MasterController {
    public function rootAction(Request $request) {
        $menu = $this->getDoctrine()->getRepository('EkologiaMenuBundle:Menu')->findParent('root', $request->getLocale());
        return $this->render('EkologiaMenuBundle:View:navbar.html.twig', array('menu' => $menu));
    }
}
