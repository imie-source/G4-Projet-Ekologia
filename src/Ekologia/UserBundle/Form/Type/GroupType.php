<?php

namespace Ekologia\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Ekologia\UserBundle\Entity\UserRepository;

class GroupType extends AbstractType {
    private $userManager;
    
    public function __construct(UserRepository $userRepository) {
        $this->userManager = $userRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text', array(
            'required' => true,
            'label' => 'ekologia.user.group.name'
        ))->add('description', 'text', array(
            'required' => false,
            'label' => 'ekologia.user.group.description'
        ))->add('administrator', 'entity', array(
            'class' => 'EkologiaUserBundle:User',
            'property' => 'username',
            'choices' => $this->userManager->findPossibleGroupAdministrators(),
            'required' => true,
            'label' => 'ekologia.user.group.administrator'
        ))->add('submit', 'submit', array(
            'label' => 'ekologia.user.group.type.submit'
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