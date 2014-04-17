<?php

namespace Ekologia\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('EkologiaMainBundle:Welcome:index.html.twig');
    }
}
