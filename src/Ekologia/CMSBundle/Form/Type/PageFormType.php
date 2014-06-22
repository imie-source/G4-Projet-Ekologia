<?php

namespace Ekologia\CMSBundle\Form\Type;

use Ekologia\ArticleBundle\Form\Type\ArticleFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the base form for a Page type
 */
class PageFormType extends ArticleFormType {
    /**
     * @Override
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * @Override
     */
    public function getName()
    {
        return 'ekologia_cms_page';
    }
    
    /**
     * @Override
     */
    protected function versionFormType() {
        return 'ekologia_cms_version';
    }
}
