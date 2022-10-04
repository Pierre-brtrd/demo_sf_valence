<?php

namespace App\Controller\Frontend;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe Main Controller pour page d'accueil.
 */
class MainController extends AbstractController
{
    public function __construct(
        private ArticleRepository $repoArticle
    ) {
    }

    /**
     *  Affiche la page d'accueil.
     *
     * @return Response
     */
    #[Route('', name: 'home')]
    public function index(): Response
    {
        // Récupère tous les articles
        $articles = $this->repoArticle->findLatestArticleWithLimit(6);

        return $this->render('Frontend/Home/index.html.twig', [
            'articles' => $articles,
            'currentPage' => 'home',
        ]);
    }
}
