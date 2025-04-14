<?php
// src/Controller/ReponseController.php
namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\Propositions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{
    /**
     * @Route("/reponse/submit", name="reponse_submit", methods={"POST"})
     */
    public function submitReponses(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formData = $request->request->all();
        $now = new \DateTime();
        $userId = $this->getUser() ? $this->getUser()->getId() : null; // Si vous avez l'authentification
        
        foreach ($formData as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $questionId = (int) substr($key, strlen('question_'));
                $question = $entityManager->getRepository(Question::class)->find($questionId);
                
                if (!$question) {
                    continue;
                }
                
                // Traiter selon le type de question
                switch ($question->getType()->getId()) {
                    case 1: // Choix unique (radio)
                        $this->saveReponse($question, $value, $entityManager, $now);
                        break;
                        
                    case 2: // Texte
                        if (!empty($value)) {
                            $reponse = new Reponse();
                            $reponse->setIdQuestion($question);
                            $reponse->setValeurReponse($value);
                            $reponse->setIdReponse(0); // Ou null selon votre modèle
                            $reponse->setDateReponse($now);
                            $reponse->setDateModification($now);
                            $reponse->setDateSupression(new \DateTime('2099-12-31'));
                            $entityManager->persist($reponse);
                        }
                        break;
                        
                    case 3: // Choix multiple (checkbox)
                        if (is_array($value)) {
                            foreach ($value as $propositionId) {
                                $this->saveReponse($question, $propositionId, $entityManager, $now);
                            }
                        }
                        break;
                }
            }
        }
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Vos réponses ont été enregistrées.');
        return $this->redirectToRoute('votre_page_succes');
    }
    
   

private function saveReponse(Question $question, $value, EntityManagerInterface $entityManager, \DateTime $dateTime): void
{
    $reponse = new Reponse();
    $reponse->setIdQuestion($question);
    $reponse->setDateReponse($dateTime);
    $reponse->setDateModification($dateTime);
    
    // Si c'est un champ texte
    if ($question->getType()->getId() == 2) {
        $reponse->setValeurReponse($value);
        // Pas de proposition pour les questions texte
    } else {
        // Si c'est un choix (radio/checkbox)
        $proposition = $entityManager->getRepository(Propositions::class)->find($value);
        if ($proposition) {
            $reponse->setProposition($proposition);
            $reponse->setValeurReponse($proposition->getLibelle()); // Optionnel si vous voulez stocker aussi le texte
        }
    }
    
    $entityManager->persist($reponse);
}
}