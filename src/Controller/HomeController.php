<?php

// HomeController.php
namespace App\Controller;

use App\Entity\Item;
use App\Utils\ItemTemplateMapping;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    // HomeController.php
    public function index(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        $items = $this->entityManager->getRepository(Item::class)->findAll();
        
        // Vérifier si un item spécifique est demandé, sinon ne rien afficher par défaut
        $defaultContent = null;
    
        // Vérifier s'il y a un paramètre 'itemId' dans la requête
        $itemId = $request->query->get('itemId');
    
        if ($itemId) {
            $item = $this->entityManager->getRepository(Item::class)->find($itemId);
            if ($item) {
                // Récupérer le contenu spécifique à cet item
                $templateMapping = ItemTemplateMapping::getMapping();
                $nomItem = $item->getNomItem();
                if (array_key_exists($nomItem, $templateMapping)) {
                    $template = $templateMapping[$nomItem];
                    $defaultContent = $this->renderView($template, ['item' => $item, 'items' => $items]);
                }
            }
        }
    
        return $this->render('home/index.html.twig', [
            'items' => $items,
            'defaultContent' => $defaultContent, // Affiche ou non le contenu spécifique
        ]);
    }
    

}