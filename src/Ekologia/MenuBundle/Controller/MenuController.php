<?php

namespace Ekologia\MenuBundle\Controller;

use Ekologia\MainBundle\Controller\MasterController;

class MenuController extends MasterController {

    public function indexAction() {
        return $this->render('EkologiaMenuBundle:GestionMenus:arborescenceMenu.html.twig');
    }

    /**
     * Get the Json from the tree
     */
    public function treePageAction($parent){
        $em = $this->getDoctrine()->getManager();
        $listeMenu = $em->getRepository("EkologiaMenuBundle:Menu")->findOneBy(array('parent' => null, 'name' => $parent));
        $treeMenu = $this->listeMenuToTree($listeMenu->getChildren());
        return $this->jsonResponse($treeMenu);
    }
    
    /**
     * Convert a list to a tree
     */
    public function listeMenuToTree($listeMenu) {
        $result = array();

        foreach ($listeMenu as $menu) {
            $result[] = array(
                'id' => $menu->getId(),
                'name' => $menu->getName(),
                'route' => $menu->getRoute(),
                'parameters' => $menu->getParameters(),
                'children' => $this->listeMenuToTree($menu->getChildren())
            );
        }
        return $result;
    }

    /**
     * à ajouter
     * Create action (création avec champ vide sauf champ obligatoires)
     * Update action (sera le plus utilisé, récupération des données avec ajax ?)
     * Delete action (delete par l'id     
     */
}
