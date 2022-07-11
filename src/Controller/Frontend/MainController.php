<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe Main Controller pour page d'accueil
 */
class MainController extends AbstractController
{
    /**
     *  Affiche la page d'accueil
     * 
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        $data = [
            'nom' => "Pierre",
            'age' => 25,
            'ville' => "Chambéry"
        ];

        return $this->render('Home/index.html.twig', ['data' => $data]);
    }
}
