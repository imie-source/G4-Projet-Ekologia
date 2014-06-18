<?php

namespace Ekologia\ArticleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Describe the base form for version of comment type of any article.
 */
class CommentFormType extends AbstractType {
    /**
     * @Override
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', 'textarea', array('required' => true, 'label' => 'ekologia.article.form.comment.text.label'));
        
        if ($options['withTitle']) {
            $builder->add('title', 'text', array('required' => true, 'label' => 'ekologia.article.form.comment.title.label'));
        }
    }

    /**
     * @Override
     */
    public function getName()
    {
        return 'ekologia_article_comment';
    }
    
    /**
     * @Override
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'withTitle' => false
        ));
    }
}