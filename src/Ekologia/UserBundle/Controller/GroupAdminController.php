<?php
namespace Ekologia\UserBundle\Controller;

use Ekologia\MainBundle\Controller\MasterController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GroupAdminController extends MasterController {
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction() {
        return $this->render('EkologiaUserBundle:Group:admin.html.twig', array(
            'formCreate' => $this->createForm('ekologia_user_grouptype')->createView(),
            'formUpdate' => $this->createForm('ekologia_user_grouptype')->createView(),
            'formDelete' => $this->createForm('ekologia_main_deletetype')->createView(),
        ));
    }
}
