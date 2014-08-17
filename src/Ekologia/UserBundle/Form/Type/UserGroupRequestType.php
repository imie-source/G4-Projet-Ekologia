<?php
/**
 * Created by PhpStorm.
 * User: siteko
 * Date: 16/08/14
 * Time: 09:57
 */

namespace Ekologia\UserBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserGroupRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('confirm', 'checkbox', array(
            'required' => true,
            'label' => 'ekologia.user.usergroup.request.confirm.label'
        ))->add('submit', 'submit', array(
            'label' => 'ekologia.user.usergroup.request.submit'
        ));
    }

    public function getName() {
        return 'ekologia_usergroup_request';
    }
}
