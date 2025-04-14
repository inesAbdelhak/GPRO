<?php
// src/EventListener/ConnexionListener.php


namespace App\EventListener;

use App\Entity\Connexion;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use DateTimeZone;
use DateTimeImmutable;

use Symfony\Component\HttpFoundation\RequestStack;

class ConnexionListener
{
    private $entityManager;
    private $tokenStorage;
    private $requestStack;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage , RequestStack $requestStack, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->session = $session;
        
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $token = $this->tokenStorage->getToken();

        if ($token && $token->getUser() instanceof User) {

            $user = $token->getUser();

            if (!$this->session->get('connexion_recorded')) {
                $connexion = new Connexion();
                $connexion->setUser($user);
                $connexion->setDateConnexion(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
                $this->entityManager->persist($connexion);
                $this->entityManager->flush();

                $this->session->set('connexion_recorded', true);





            }
        }


    }
}

