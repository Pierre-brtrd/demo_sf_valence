<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class Admin Controller
 * @Route("/admin")
 */
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $repoArticle
    ) {
    }

    #[Route('', name: 'admin')]
    public function index(): Response
    {
        // Récupérer tous les articles
        $articles = $this->repoArticle->findAll();

        return $this->render('Backend/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/create', name: 'admin.article.create')]
    public function createArticle(Request $request, Security $security): Response|RedirectResponse
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setUser($security->getUser());

            $this->repoArticle->add($article, true);
            $this->addFlash('success', 'Article créé avec succès');

            return $this->redirectToRoute('admin');
        }

        return $this->render('Backend/Article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/edit/{id}', name: 'admin.article.edit', methods: 'GET|POST')]
    public function editArticle($id, Request $request)
    {
        $article = $this->repoArticle->find($id);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->repoArticle->add($article, true);
            $this->addFlash('success', 'Article modifié avec succès');

            return $this->redirectToRoute('admin');
        }

        return $this->render('Backend/Article/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/delete/{id}', name: 'admin.article.delete', methods: 'DELETE|POST')]
    public function deleteArticle($id, Article $article, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get("_token"))) {
            $this->repoArticle->remove($article, true);
            $this->addFlash('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute('admin');
    }
}
