<?php

namespace Ekologia\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CUserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text', array('label' => 'ekologia.user.registration.cuser.name.label'));
        $builder->add('activity', 'text', array('label' => 'ekologia.user.registration.cuser.activity.label'));
        $builder->add('type', 'choice', array(
            'choices' => array(
                'association' => 'ekologia.user.cusertype.type.association',
                'business' => 'ekologia.user.cusertype.type.business',
                'ngo' => 'ekologia.user.cusertype.type.ngo',
                'media' => 'ekologia.user.cusertype.type.media',
                'syndicate' => 'ekologia.user.cusertype.type.syndicate'
            ),
            'label' => 'ekologia.user.registration.cuser.type.label'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ekologia\UserBundle\Entity\CUser'
        ));
    }

    public function getName() {
        return 'ekologia_user_pusertype';
    }
}
