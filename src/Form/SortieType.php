<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class SortieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateType::class)
            ->add('dateLimiteInscription')
            ->add('duree')
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('infosSortie')
            ->add('campus', EntityType::class,['class'=>Campus::class,'mapped' => false,'choice_label'=>'nom'])
            ->add('ville', EntityType::class,
                [
                    'class'=>Ville::class,
                    'mapped' => false,
                    'query_builder' => function(VilleRepository $villeRepository ){ return $villeRepository->createQueryBuilder('c')->orderBy('c.nom');}
                ]
            )
            ->add('lieu' , EntityType::class,['class'=>Lieu::class,'choice_label'=>'nom' ])
            ->add('etat', EntityType::class,['class' => Etat::class,'choice_label'=>'libelle']);

    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }


}
