<?php

namespace Ekologia\ArticleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines a sample form to delete an entity (with security token)
 */
class DeleteFormType extends AbstractType {
    /**
     * @Override
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'collection', array(
                'choices' => $options['deletableList'],
                'required' => true
            ));
    }

    /**
     * @Override
     */
    public function getName()
    {
        return 'ekologia_article_delete';
    }
    
    /**
     * @Override
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'deletableList' => null
        ));
    }
}
