<?php

namespace App\Controller\Frontend;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository
    ) {
    }

    #[Route('/compte', name: 'app.user.compte')]
    public function compte(): Response
    {
        $user = $this->security->getUser();

        return $this->render('Frontend/User/compte.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/compte/edit', name: 'app.user.edit', methods: ['GET', 'POST'])]
    public function editAccount(Request $request): Response|RedirectResponse
    {
        $user = $this->security->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);
            $this->addFlash('success', 'Votre compte a été modifié avec succès');

            return $this->redirectToRoute('app.user.compte');
        }

        return $this->renderForm('Backend/User/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }
}
