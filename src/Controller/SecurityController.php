<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route('/connexion', name: 'app_connexion')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $formView = $this->createForm(LoginType::class);

        return $this->render('security/connexion.html.twig', [
            'formView' => $formView->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

        #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security, RouterInterface $router) : Response
    {
        $security->logout(false);
        return new RedirectResponse($router->generate("app_home"));
    }
}
