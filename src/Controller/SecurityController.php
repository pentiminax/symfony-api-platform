<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login/{username}', name: 'app_login')]
    public function login(Security $security, ?User $user): Response
    {
        if ($user) {
            $security->login($user);
        }

        return $this->redirectToRoute('api_doc');
    }
}