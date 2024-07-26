<?php

namespace App\Form;

use App\Entity\Animaux;
use App\Entity\Nourriture;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AlimentationAnimauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('animal', EntityType::class, [
            'class' => Animaux::class,
            'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.Prenom', 'ASC');
                },
            'choice_label' => 'Prenom',
        ])
            ->add('nourriture')
            ->add('quantite')
            ->add('date', null, [
                'widget' => 'single_text'
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nourriture::class,
        ]);
    }
}
