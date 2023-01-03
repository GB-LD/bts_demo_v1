<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingUserController extends AbstractController
{
    #[Route('/parametres', name: 'app_setting_user')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $formUserSettings = $form->createView();
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $entityManager->flush();
        }

        return $this->render('setting_user/settingUser.html.twig', [
            'formUserSettings' => $formUserSettings
        ]);
    }
}
