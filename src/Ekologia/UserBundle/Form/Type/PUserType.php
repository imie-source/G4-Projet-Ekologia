<?php

namespace Ekologia\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PUserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstname', 'text');
        $builder->add('lastname', 'text');
        $builder->add('birthdate', 'date');
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
