<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    /**
     * Constructeur of UserController
     *
     * @param UserRepository $repoUser
     */
    public function __construct(private UserRepository $repoUser)
    {
    }

    /**
     * Page for list user admin
     *
     * @return Response
     */
    #[Route('', name: 'admin.user.index')]
    public function indexUser(): Response
    {
        $users = $this->repoUser->findAll();

        return $this->render('Backend/User/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/{id}/delete', name: 'admin.user.delete', methods: ['POST'])]
    public function deleteUser(?User $user, Request $request): RedirectResponse
    {
        if (!$user instanceof User) {
            $this->addFlash('error', 'User not found');

            return $this->redirectToRoute('admin.user.index');
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $this->repoUser->remove($user, true);

            $this->addFlash('success', 'Utilisateur supprimé avec succès');

            return $this->redirectToRoute('admin.user.index');
        }
    }
}
