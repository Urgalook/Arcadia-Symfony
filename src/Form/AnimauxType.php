<?php

namespace App\Form;

use App\Entity\Animaux;
use App\Entity\Espece;
use App\Entity\Habitat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Prenom')
            ->add('Espece', EntityType::class, [
                'class' => Espece::class,
'choice_label' => 'nom',
            ])
            ->add('Habitat', EntityType::class, [
                'class' => Habitat::class,
'choice_label' => 'nom',
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animaux::class,
        ]);
    }
}
