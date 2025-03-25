<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('artist',EntityType::class,[
                'class' => Artist::class, //cette ligne du formulaire demande un artiste deja existant
                'choice_label' => 'name', //affiche les nom des artiste dans chaque etiquette
                'placeholder' => 'Choisissez un artiste',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
