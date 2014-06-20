<?php

namespace Ekologia\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class WelcomeController extends MasterController {
    public function indexAction(Request $request) {
        $pageHomepage = $this->getDoctrine()
                             ->getRepository('EkologiaCMSBundle:Page')
                             ->findOneBy(array('canonical' => 'homepage', 'language' => strtoupper($request->getLocale())));
        return $this->render('EkologiaMainBundle:Welcome:index.html.twig', array('page' => $pageHomepage));
    }
}
