<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('rue')
            ->add('latitude')
            ->add('longitude')
            ->add('ville',EntityType::class, [
        'class' => Ville::class,
        'label' => "Ville :",
        'placeholder' => '--- Sélectionner ---',
        'choice_label' => 'nom',
        'query_builder' => function (VilleRepository $villeRepository){
            return $villeRepository->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
        },
        'constraints' => new NotBlank()

    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
