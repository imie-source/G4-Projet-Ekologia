<?php

namespace Ekologia\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CUserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text');
        $builder->add('activity', 'text');
        $builder->add('type', 'choice', array(
            'choices' => array(
                'association' => 'ekologia.user.cusertype.type.association',
                'business' => 'ekologia.user.cusertype.type.business',
                'ngo' => 'ekologia.user.cusertype.type.ngo',
                'media' => 'ekologia.user.cusertype.type.media',
                'syndicate' => 'ekologia.user.cusertype.type.syndicate'
            )
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
