<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profils", name="profil_")
 */
class ProfilsController extends AbstractController
{
    /**
     * @Route("", name="details")
     */
    public function details(): Response
    {
        return $this->render('profils/details.html.twig', [
        ]);
    }

    /**
     * @Route("/afficher/{id}", name="afficher_profil")
     */
    public function afficher(): Response
    {
        return $this->render('profils/afficher.html.twig', [
        ]);
    }
}
