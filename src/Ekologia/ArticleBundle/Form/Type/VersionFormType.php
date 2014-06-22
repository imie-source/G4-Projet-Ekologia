<?php

namespace Ekologia\ArticleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Describe the base form for version of article type.
 */
class VersionFormType extends AbstractType {
    /**
     * @Override
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array('required' => true, 'label' => 'ekologia.article.form.article.title.label'))
                ->add('content', 'textarea', array('required' => true, 'label' => 'ekologia.article.form.version.content.label'))
                ->add('active', 'checkbox', array('label' => 'ekologia.article.form.version.active.label'));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ekologia\ArticleBundle\Entity\Version',
        ));
    }

    /**
     * @Override
     */
    public function getName()
    {
        return 'ekologia_article_version';
    }
}
