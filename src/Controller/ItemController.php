<?php
namespace App\Controller;
use App\Utils\ItemTemplateMapping;
use App\Entity\Item;
use App\Entity\Projet;
use App\Entity\Question;
use App\Entity\Proposition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route("/item/{itemId}", name: "app_item")]
    public function show(Request $request, int $itemId): Response
    {
        // Récupération de l'item
        $item = $this->entityManager->getRepository(Item::class)->find($itemId);
        $projets = $this->entityManager->getRepository(Projet::class)->findAll();
        
        if (!$item) {
            throw $this->createNotFoundException('Item non trouvé');
        }
        
        // Récupération des questions associées à l'item
        $questions = $item->getQuestions();
        
        // Pour la sidebar - Transformation des entités en tableaux associatifs
        $itemsRaw = $this->entityManager->getRepository(Item::class)->findAll();
        $items = [];
        foreach ($itemsRaw as $itemEntity) {
            $items[] = [
                'id' => $itemEntity->getId(),
                'nomItem' => $itemEntity->getNomItem()
            ];
        }
        
        // Vérification du mapping des templates
        $templateMapping = ItemTemplateMapping::getMapping();
        $nomItem = $item->getNomItem();
        
        if (!array_key_exists($nomItem, $templateMapping)) {
            throw $this->createNotFoundException('Template non trouvé pour cet item');
        }
        
        $template = $templateMapping[$nomItem];
        
        // Passer les questions et leurs propositions à la vue
        $data = [];
        foreach ($questions as $question) {
            $questionCondition = $question->getQuestionCondition();
            $propositions = $question->getPropositions()->toArray();
            $data[] = [
                'question' => $question,
                'propositions' => $propositions,
                'isConditional' => $questionCondition !== null,
                'conditionPropositionId' => $questionCondition ? $questionCondition->getId() : null
            ];
        }
        
        return $this->render($template, [
            'item' => $item,
            'questions' => $data,
            'items' => $items, // Maintenant un tableau d'arrays avec 'id' et 'nomItem'
            'projets' => $projets,
        ]);
    }
}