<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Repository\EtatRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'label' => "Etat : ",
                'placeholder' => '---Sélectionner---',
                'choice_label' => 'libelle', //champ que je veux dans mon select
                //On peut utiliser le query builder
                'query_builder' => function (EtatRepository $etatRepository) {
                    return $etatRepository->createQueryBuilder('e')->orderBy('e.libelle', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
