<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
{
    return new RedirectResponse($this->urlGenerator->generate('home'));
}

private $urlGenerator;

public function __construct(UrlGeneratorInterface $urlGenerator)
{
    $this->urlGenerator = $urlGenerator;
}

#[Route(path: '/deconnexion', name: 'app_logout', methods: ['POST'])]
public function logout(Request $request): JsonResponse
{
    // Invalidate the session
    $request->getSession()->invalidate();
    
    return new JsonResponse([
        'status' => 'success',
        'message' => 'Déconnexion réussie'
    ]);
}


}
