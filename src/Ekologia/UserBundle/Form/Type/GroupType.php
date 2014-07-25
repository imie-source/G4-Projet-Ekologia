<?php

namespace Ekologia\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class GroupType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text');
        $builder->add('description', 'text');
        $builder->add('administrator', 'entity', array(
            'class' => 'EkologiaUserBundle:User',
            'property' => 'username',
            'query_builder' => function(EntityRepository $rep) {
                return $rep->findPossibleGroupAdministrators();
            }
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ekologia\UserBundle\Entity\Group'
        ));
    }

    public function getName() {
        return 'ekologia_user_grouptype';
    }
}