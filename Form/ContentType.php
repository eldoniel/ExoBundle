<?php

namespace UJM\ExoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('media', 'text', array ('required' => true));
        $builder->add('position', 'hidden', array('data' => 1));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array (
            'data_class' => 'UJM\ExoBundle\Entity\ExerciseGrammar\Content',
        ));
    }
    
    public function getName()
    {
        return 'ujm_exo_content';
    }
}
