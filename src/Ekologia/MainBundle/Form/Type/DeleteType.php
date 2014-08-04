<?php
namespace Ekologia\MainBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\BaseType;

class DeleteType extends BaseType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('confirm', 'checkbox', array('required' => true, 'label' => 'ekologia.main.form.delete.confirm'));
        $builder->add('submit', 'submit', array('label' => 'ekologia.main.form.delete.submit'));
    }

    public function getName() {
        return 'ekologia_main_deletetype';
    }
}