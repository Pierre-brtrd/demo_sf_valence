<?php

namespace App\Controller\Frontend;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe Main Controller pour page d'accueil
 */
class MainController extends AbstractController
{
    public function __construct(
        private ArticleRepository $repoArticle
    ) {
    }

    /**
     *  Affiche la page d'accueil
     * 
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        // Récupère tous les articles
        $articles = $this->repoArticle->findAll();

        return $this->render('Frontend/Home/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
