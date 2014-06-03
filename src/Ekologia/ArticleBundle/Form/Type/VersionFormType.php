<?php

namespace Ekologia\ArticleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Describe the base form for version of article type.
 */
class VersionFormType extends AbstractType {
    /**
     * @Override
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea', array('required' => true, 'label' => 'ekologia.article.form.version.content.label'));
    }

    /**
     * @Override
     */
    public function getName()
    {
        return 'ekologia_article_version';
    }
}
