<?php

namespace App\EventListener;

use App\Repository\ItemRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TemplateListener implements EventSubscriberInterface
{
    private $twig;
    private $itemRepository;

    public function __construct(Environment $twig, ItemRepository $itemRepository)
    {
        $this->twig = $twig;
        $this->itemRepository = $itemRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event)
    {
        $items = $this->itemRepository->findAll();
        $this->twig->addGlobal('items', $items);
    }
}