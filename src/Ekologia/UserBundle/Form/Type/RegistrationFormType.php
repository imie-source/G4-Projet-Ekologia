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

        $builder->add('addressStreet', 'text', array('required' => false, 'label' => 'ekologia.user.registration.address.street.label'));
        $builder->add('addressZipCode', 'integer', array('required' => false, 'label' => 'ekologia.user.registration.address.zip-code.label'));
        $builder->add('addressCity', 'text', array('required' => false, 'label' => 'ekologia.user.registration.address.city.label'));
        $builder->add('avatar', 'url', array('required' => false, 'label' => 'ekologia.user.registration.avatar.label'));
        $builder->add('phoneNumber', 'text', array('required' => false, 'label' => 'ekologia.user.registration.phone-number.label'));
        $builder->add('description', 'textarea', array('required' => false, 'label' => 'ekologia.user.registration.description.label'));
        $builder->add('country', 'text', array('required' => true, 'label' => 'ekologia.user.registration.country.label'));
        $builder->add('interests', 'collection', array(
            'type'         => 'text',
            'options'      => array('required' => false),
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false
        ));
        $builder->add('userType', 'choice', array(
            'choices' => array(
                'cuser' => 'ekologia.user.registrationformtype.usertype.cuser',
                'puser' => 'ekologia.user.registrationformtype.usertype.puser'
            ), 'required' => true,
            'label' => 'ekologia.user.registration.user-type.label'
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