<?php

namespace Ekologia\MainBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Redefines some features of Symfony2
 *
 * @author G4U
 */
class MasterController extends Controller {
    /**
     * Creates and returns a JSON response including given data
     * 
     * @param array $data The data to include
     * @return \Symfony\Component\HttpFoundation\JsonResponse The generated JSON response
     */
    protected function jsonResponse(array $data) {
        $response = new JsonResponse();
        $response->setData($data);
        return $response;
    }
    
    /**
     * Creates and returns a Redirection Response in terms of given parameters.
     * The first one is used as route name and followers as route parameters. <br/>
     * Exemple:
     * <code>
     * $this->redirectResponse('my-page-name', array('param1' => 'Hello', 'param2' => 'World'))
     * </code>
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse The generated redirection or null if any argument is given
     */
    protected function redirectResponse($url, $parameters=array()) {
        return $this->redirect($this->generateUrl($url, $parameters));
    }
}
