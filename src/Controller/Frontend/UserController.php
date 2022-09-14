<?php

namespace App\Controller\Frontend;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    #[Route('/compte', name: 'app.user.compte')]
    public function compte(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('Frontend/User/compte.html.twig', [
            'user' => $user,
        ]);
    }
}
