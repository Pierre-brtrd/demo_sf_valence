<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Admin Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * Article repository to find article object
     * 
     * @var ArticleRepository
     */
    private $repoArticle;

    /**
     * User repository to find user object
     * 
     * @var UserRepository
     */
    private $repoUser;

    /**
     * Entity manager interface
     *
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ArticleRepository $repoArticle, UserRepository $repoUser, EntityManagerInterface $em)
    {
        $this->repoArticle = $repoArticle;
        $this->repoUser = $repoUser;
        $this->em = $em;
    }

    #[Route('', name: 'admin')]
    public function index(): Response
    {
        // Récupérer tout les users
        $users = $this->repoUser->findAll();

        // Récupérer tout les articles
        $articles = $this->repoArticle->findAll();

        return $this->render('Backend/index.html.twig', [
            'articles' => $articles,
            'users' => $users,
        ]);
    }

    #[Route('/article/create', name: 'admin.article.create')]
    public function createArticle(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $this->em->flush();
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
            $this->em->persist($article);
            $this->em->flush();
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
            $this->em->remove($article);
            $this->em->flush();
            $this->addFlash('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute('admin');
    }
}
