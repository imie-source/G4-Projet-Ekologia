<?php

namespace Ekologia\CMSBundle\Form\Type;

use Ekologia\ArticleBundle\Form\Type\VersionFormType as AbstractVersionFormType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Describe the base form for version of article type.
 */
class VersionFormType extends AbstractVersionFormType {
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
