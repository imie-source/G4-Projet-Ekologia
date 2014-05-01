<?php

namespace Ekologia\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('addressStreet', 'text', array('required' => false));
        $builder->add('addressZipCode', 'integer', array('required' => false));
        $builder->add('addressCity', 'text', array('required' => false));
        $builder->add('avatar', 'url', array('required' => false));
        $builder->add('phoneNumber', 'text', array('required' => false));
        $builder->add('description', 'textarea', array('required' => false));
        $builder->add('country', 'text', array('required' => true));
        $builder->add('tags', 'entity', array(
            'class' => 'EkologiaMainBundle:Tag',
            'query_builder' => function(EntityRepository $repository) {
                return $repository->createQueryBuilder('t');
            },
            'multiple' => true,
            'required' => false
        ));
        $builder->add('userType', 'choice', array(
            'choices' => array(
                'cuser' => 'ekologia.user.registrationformtype.usertype.cuser',
                'puser' => 'ekologia.user.registrationformtype.usertype.puser'
            ), 'required' => true
        ));
        $builder->add('puser', new PUserType(), array('required' => false));
        $builder->add('cuser', new CUserType(), array('required' => false));
    }

    public function getName()
    {
        return 'ekologia_user_registration';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'cascade_validation' => true,
        ));
    }
}