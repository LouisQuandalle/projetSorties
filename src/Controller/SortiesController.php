<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sorties", name="sorties_")
 */
class SortiesController extends AbstractController
{
    /**
     * @Route("/afficher/{id}", name="afficher")
     */
    public function afficher(): Response
    {
        return $this->render('sorties/afficher.html.twig', [
        ]);
    }

    /**
     * @Route("/annuler", name="annuler")
     */
    public function annuler(): Response
    {
        return $this->render('sorties/annuler.html.twig', [
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(): Response
    {
        return $this->render('sorties/creer.html.twig', [
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifier(): Response
    {
        return $this->render('sorties/modifier.html.twig', [
        ]);
    }
}
