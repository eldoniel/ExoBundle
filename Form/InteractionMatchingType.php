<?php

namespace UJM\ExoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Claroline\CoreBundle\Entity\User;

class InteractionMatchingType extends AbstractType
{
    private $user;
    private $catID;

    public function __construct(User $user, $catID = -1)
    {
        $this->user  = $user;
        $this->catID = $catID;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add(
                'interaction', new InteractionType(
                    $this->user, $this->catID
                )
            );
        
        $builder
            ->add(
                'typeMatching', 'entity', array(
                    'class' => 'UJM\\ExoBundle\\Entity\\TypeMatching',
                    'label' => 'TypeMatching.value'
                )
            );
        
        $builder
            ->add(
                'labels', 'collection', array(
                    'type' => new LabelType,
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true
                )
            );
        
        $builder
            ->add(
                'proposals', 'collection', array(
                    'type' => new ProposalType,
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true
                )
            );
        
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'UJM\ExoBundle\Entity\InteractionMatching',
                'cascade_validation' => true
            )
        );
    }
    
    public function getName()
    {
        return 'ujm_exobundle_interactionmatchingtype';
    }
}