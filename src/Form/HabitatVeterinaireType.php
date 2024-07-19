<?php

namespace App\Form;

use App\Entity\Habitat;
use App\Entity\HabitatVeterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HabitatVeterinaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Habitat', EntityType::class, [
                'class' => Habitat::class,
                'choice_label' => 'Nom',
            ])
            ->add('Commentaire')
            ->add('Date', null, [
                'widget' => 'single_text'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HabitatVeterinaire::class,
        ]);
    }
}
