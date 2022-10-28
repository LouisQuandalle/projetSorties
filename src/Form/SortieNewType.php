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



class SortieNewType extends AbstractType
{
    private LieuRepository $lieuRepository;
    private VilleRepository $villeRepository;

    public function __construct(LieuRepository $lieuRepository, VilleRepository $villeRepository){
        $this->lieuRepository = $lieuRepository;
        $this->villeRepository = $villeRepository;
    }

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
                    //'placeholder' => 'Choisi une ville',
                    'class'=>Ville::class,
                    'mapped' => false,
                    'query_builder' => function(VilleRepository $villeRepository ){ return $villeRepository->createQueryBuilder('c')->orderBy('c.nom');}
                ]
            )
            ->add('etat', EntityType::class,['class' => Etat::class,'mapped' => false,'choice_label'=>'libelle']);

        /*
                $formModifier = function (FormInterface $form, Lieu $lieu = null) {
                    $lieu = $lieu === null ? [] : $this->lieuRepository->findByCountryOrderedByAscName($ville);
                    $form->add('lieu', EntityType::class, [
                        'class' => Lieu::class,
                        'choice_label' => 'nom',
                        /*'disabled' => $ville === null,*/
        /*                'placeholder' => 'Choisi un lieu',
                        'choices' => $lieu
                    ]);
                };

                $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function(FormEvent $event) use ($formModifier)
                    {
                        $data = $event->getData();
                        $formModifier($event->getForm(), $data->getLieu());
                    }
                );

                $builder->get('ville')->addEventListener(
                    FormEvents::POST_SUBMIT,
                    function (FormEvent $event) use ($formModifier) {
                        $ville = $event->getForm()->getData();
                        $formModifier($event->getForm()->getParent(), $ville);
                    }
                );
        */

    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }


}
