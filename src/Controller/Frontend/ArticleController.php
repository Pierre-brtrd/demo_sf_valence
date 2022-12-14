<?php

namespace App\Controller\Frontend;

use App\Data\SearchData;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/liste', name: 'app.article.index', methods: ['GET', 'POST'])]
    public function listArticle(
        ArticleRepository $repoArticle,
        Request $request
    ): Response|JsonResponse {
        $data = new SearchData();

        $page = $request->get('page', 1);
        $data->setPage($page);

        $form = $this->createForm(SearchArticleType::class, $data);
        $form->handleRequest($request);

        $articles = $repoArticle->findSearchData($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_articles.html.twig', [
                    'articles' => $articles,
                ]),
                'sortable' => $this->renderView('Components/_sortable.html.twig', [
                    'articles' => $articles,
                ]),
                'count' => $this->renderView('Components/_count.html.twig', [
                    'articles' => $articles,
                ]),
                'pagination' => $this->renderView('Components/_pagination.html.twig', [
                    'articles' => $articles,
                ]),
                'pages' => ceil($articles->getTotalItemCount() / $articles->getItemNumberPerPage()),
            ]);
        }

        return $this->renderForm('Frontend/Article/index.html.twig', [
            'articles' => $articles,
            'form' => $form,
            'currentPage' => 'articles',
        ]);
    }

    #[Route('/details/{slug}', name: 'app.article.show', methods: ['GET', 'POST'])]
    public function showArticle(
        ?Article $article,
        Request $request,
        Security $security,
        CommentRepository $repoComment
    ): Response|RedirectResponse {
        if (!$article instanceof Article) {
            $this->addFlash('error', 'Article non trouv??');

            return $this->redirectToRoute('home');
        }

        $comments = $repoComment->findByArticle($article->getId(), true);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($security->getUser())
                ->setActive(true)
                ->setArticle($article);

            $repoComment->add($comment, true);

            $this->addFlash('success', 'Commentaire post?? avec succ??s');

            return $this->redirectToRoute('app.article.show', [
                'slug' => $article->getSlug(),
            ]);
        }

        return $this->renderForm('Frontend/Article/show.html.twig', [
            'article' => $article,
            'form' => $form,
            'comments' => $comments,
        ]);
    }
}
