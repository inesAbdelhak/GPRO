<?php
namespace App\Controller;
use App\Entity\Item;
use App\Entity\Question;
use App\Entity\Propositions;
use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DescriptionController extends AbstractController
{
    #[Route('/description', name: 'app_description')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'item avec l'ID 1
        $item = $entityManager->getRepository(Item::class)->find(1);
       
        if (!$item) {
            throw $this->createNotFoundException('Item #1 introuvable');
        }
       
        // Récupérer les questions liées à cet item
        $questions = $item->getQuestions();
       
        // Préparer les données pour le template
        $questionsData = [];
        foreach ($questions as $question) {
            $questionCondition = $question->getQuestionCondition();
            $propositions = $question->getPropositions()->toArray();
            $questionsData[] = [
                'question' => $question,
                'propositions' => $propositions,
                'isConditional' => $questionCondition !== null,
                'conditionPropositionId' => $questionCondition ? $questionCondition->getId() : null
            ];
        }
       
        return $this->render('description/index.html.twig', [
            'questions' => $questionsData,
            'item' => $item,
            'controller_name' => 'DescriptionController'
        ]);
    }

    #[Route('/description/save', name: 'app_description_save', methods: ['POST'])]
    public function saveDescription(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'ID du projet
        $projetId = $request->request->get('projet_id');
        
        // Traiter les données du formulaire ici
        $postData = $request->request->all();
        
        // Logique pour sauvegarder les réponses
        // ...
        
        // Rediriger vers la page de détails du projet
        return $this->redirectToRoute('app_projet_details', ['id' => $projetId]);
    }
}