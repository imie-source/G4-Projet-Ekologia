<?php

namespace Ekologia\MainBundle\Controller;

class WelcomeController extends MasterController
{
    public function indexAction()
    {
        return $this->render('EkologiaMainBundle:Welcome:index.html.twig');
    }
}
