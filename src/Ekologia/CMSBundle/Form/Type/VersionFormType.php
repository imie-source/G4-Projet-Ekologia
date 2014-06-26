<?php

namespace Ekologia\CMSBundle\Form\Type;

use Ekologia\ArticleBundle\Form\Type\VersionFormType as AbstractVersionFormType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Describe the base form for version of article type.
 */
class VersionFormType extends AbstractVersionFormType {
    /**
     * @Override
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('css', 'textarea', array('required' => false, 'label' => 'ekologia.article.form.article.css.label'))
                ->add('js', 'textarea', array('required' => false, 'label' => 'ekologia.article.form.article.js.label'));
    }
    
    /**
     * @Override
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ekologia\CMSBundle\Entity\Version',
        ));
    }

    /**
     * @Override
     */
    public function getName()
    {
        return 'ekologia_cms_version';
    }
}
