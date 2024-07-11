<?php

namespace App\Form;

use App\Entity\Animaux;
use App\Entity\Veterinaire;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VeterinaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat')
            ->add('nourriture')
            ->add('grammage')
            ->add('date', null, [
                'widget' => 'single_text'
            ])
            ->add('remarque')
            ->add('Animal', EntityType::class, [
                'class' => Animaux::class,
'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.Prenom', 'ASC');
                },
            'choice_label' => 'Prenom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Veterinaire::class,
        ]);
    }
}
