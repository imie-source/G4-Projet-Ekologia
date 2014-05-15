<?php

namespace Ekologia\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PUserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstname', 'text', array('label' => 'ekologia.user.registration.puser.firstname.label'));
        $builder->add('lastname', 'text', array('label' => 'ekologia.user.registration.puser.lastname.label'));
        $builder->add('birthdate', 'date', array(
            'widget' => 'single_text',
            'format' => 'd/M/y',
            'label' => 'ekologia.user.registration.puser.birthdate.label'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ekologia\UserBundle\Entity\PUser'
        ));
    }

    public function getName() {
        return 'ekologia_user_pusertype';
    }
}
