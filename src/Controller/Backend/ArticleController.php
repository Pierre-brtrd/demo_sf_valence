<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class Admin Controller
 */
#[Route('/admin')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $repoArticle,
        private CommentRepository $repoComment
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

    #[Route('/article/{slug}/comments', name: 'admin.article.comments', methods: ['GET'])]
    public function adminComments(?Article $article): Response|RedirectResponse
    {
        if (!$article instanceof Article) {
            $this->addFlash('error', 'Article non trouvé, vérifiez votre url');

            return $this->redirectToRoute('admin');
        }

        $comments = $this->repoComment->findByArticle($article->getId());

        if (!$comments) {
            $this->addFlash('error', 'Aucun commentaire trouvé pour cet article');

            return $this->redirectToRoute('admin');
        }

        return $this->render('Backend/Comment/index.html.twig', [
            'comments' => $comments,
            'article' => $article,
        ]);
    }

    #[Route('/comments/switch/{id}', name: 'admin.comments.switch', methods: ['GET'])]
    public function switchVisibilityComment(?Comment $comment): Response
    {
        if (!$comment instanceof Comment) {
            return new Response('Commentaire non trouvé', 404);
        }

        $comment->setActive(!$comment->isActive());
        $this->repoComment->add($comment, true);

        return new Response('Visibilité changée avec succès', 201);
    }

    #[Route('/{id}/comments/delete', name: "admin.comments.delete", methods: ['POST', 'DELETE'])]
    public function deleteComment(?Comment $comment, Request $request): RedirectResponse
    {
        if (!$comment instanceof Comment) {
            $this->addFlash('error', 'Commentaire non trouvé');

            return $this->redirectToRoute('admin');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get('_token'))) {
            $this->repoComment->remove($comment, true);
            $this->addFlash('success', 'Commentaire supprimé avec succès');

            return $this->redirectToRoute('admin.article.comments', [
                'slug' => $comment->getArticle()->getSlug(),
            ]);
        }

        $this->addFlash('error', 'Le token n\'est pas valide');

        return $this->redirectToRoute('admin.article.comments', [
            'slug' => $comment->getArticle()->getSlug(),
        ]);
    }
}
