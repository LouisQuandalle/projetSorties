<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SearchSortieType;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="app_sortie_index")
     */
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
        $user = $this->getUser();
        #fsvdfsd
        $data = new Sortie();
        $form = $this->createForm(SearchSortieType::class, $data);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            #return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);

            $campus=$request->get('campus');
            $nom= '%' . $request->get('nom') . '%';
            $dateDebut= $request->get('dateDebut');
            $dateFin= $request->get('dateFin');
            $idUser = $user->getUserIdentifier();
            $sortieOrga = $request->get('orga');;
            $sortieInscrit = $request->get('inscrit');;
            $sortiePasInscrit = $request->get('pasInscrit');;
            $sortiePasse = $request->get('passer');;

            $sorties = $sortieRepository->search($campus, $nom, $dateDebut, $dateFin, $idUser, $sortieOrga, $sortieInscrit, $sortiePasInscrit, $sortiePasse);
        }
        else
        {
            $sorties = $sortieRepository->findAll();
        }

        return $this->render('sortie/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'sorties' => $sorties,
        ]);
    }

    /**
     * @Route("/new", name="app_sortie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SortieRepository $sortieRepository, ParticipantRepository $participantRepository, LieuRepository $lieuRepository): Response
    {
        $sortie = new Sortie();
        $lieu = $lieuRepository->findAll();

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $sortie->setOrganisateur($participantRepository->find(9)); //--------------------------------------------------------------------------------------------------------------- ICI
        $sortie->setSiteOrganisateur($participantRepository->find(9)->getCampus());

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);
            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/id/{id}", name="app_sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        $user = $this->getUser();
        $today = date("Y-m-d");

        $inscrits = $sortie->getInscrit()->toArray();
        $BoolInscrit = false;
        foreach ($inscrits as $inscrit)
        {
            if ($inscrit->getId() == $user->getUserIdentifier())
            {
                $BoolInscrit = true;
            }
        }
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'user' => $user,
            'today' => $today,
            'inscrit' => $BoolInscrit,
        ]);
    }

    /**
     * @Route("/id/{id}/edit", name="app_sortie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_sortie_delete")
     */
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {
        $sortieRepository->remove($sortie, true);

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/search", name="sortie_listeSearch")
     * @param EntityManagerInterface $entityManager
     * @param $query
     * @return Response
     */
    public function listeSearch(EntityManagerInterface $entityManager, $query)
    {
        $idUser = 1;
        $sortieOrga = false;
        $sortieInscrit = false;
        $sortiePasInscrit = false;
        $sortiePasse = false;


        if ($sortieOrga)
        {
            $query->andWhere('s.organisateur = :id')->setParameter('id', $idUser);
        }

        if ($sortieInscrit)
        {
            $query->innerJoin('s.inscrit', 'p');
            $query->andWhere('p.id = :id') ->setParameter('id', $idUser);
        }

        if ($sortiePasInscrit)
        {
            $query->innerJoin('s.inscrit', 'p');
            $query->andWhere('p.id != :id')->setParameter('id', $idUser);
        }

        if ($sortiePasse)
        {
            $query->andWhere('s.etat = 2 ');
        }

        $query = $query->getQuery();
        $sorties = $query->getResult();


        return $this->render('sortie/accueil.html.twig', [
            'sorties' => $sorties
        ]);
    }

    /**
     * @Route ("/inscription/{id}", name="sortie_inscription")
     */
    public function inscription(Sortie $sortie, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $sortie->addInscrit($participantRepository->find($user->getUserIdentifier()));
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route ("/deinscription/{id}", name="sortie_deinscription")
     */
    public function deinscription(Sortie $sortie, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $sortie->removeInscrit($participantRepository->find($user->getUserIdentifier()));
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

}
