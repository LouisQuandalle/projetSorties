<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lieux", name="lieux_")
 */
class LieuxController extends AbstractController
{
    /**
     * @Route("/campus", name="campus")
     */
    public function campus(): Response
    {
        return $this->render('lieux/campus.html.twig', [

        ]);
    }

    /**
     * @Route("/ville", name="ville")
     */
    public function ville(): Response
    {
        return $this->render('lieux/ville.html.twig', [

        ]);
    }
}
