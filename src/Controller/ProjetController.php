<?php
namespace App\Controller;
use App\Entity\User;
use App\Entity\Projet;
use App\Form\ProjetType;
use App\Entity\Item;
use App\Entity\Question;  
use App\Entity\Propositions;
use App\Repository\ProjetRepository;
use App\Repository\QuestionRepository;  
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/tableau-de-bord')]
class ProjetController extends AbstractController
{

    
    #[Route('/', name: 'app_dashboard', methods: ['GET'])]
    public function index(Request $request, ProjetRepository $projetRepository, EntityManagerInterface $entityManager): Response
    {    
        // Récupérer les 10 derniers projets, triés par ID décroissant
        $projets = $projetRepository->findBy(
            [], // critères
            ['id' => 'DESC'], // tri
            10 // limite
        );
        
        $items = $entityManager->getRepository(Item::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();
    
        if ($request->isXmlHttpRequest()) {
            // Créer un tableau de projets pour la réponse JSON
            $projetData = array_map(function($projet) {
                return [
                    'id' => $projet->getId(),
                    'titre' => $projet->getTitre(),
                    'acronyme' => $projet->getAcronyme(),
                    'isAdmin' => $this->isGranted('ROLE_ADMIN')
                ];
            }, $projets);
            
            return new JsonResponse($projetData);
        }
    
        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
            'users' => $users
        ]);
    }



// Dans ProjetController.php
#[Route('/search', name: 'app_projet_search', methods: ['GET'])]
public function search(Request $request, ProjetRepository $projetRepository): JsonResponse
{
    try {
        $searchTerm = $request->query->get('q', '');
        
        // Si le terme de recherche est vide, renvoyer les 10 derniers projets
        if (empty($searchTerm)) {
            $projets = $projetRepository->findBy(
                [], // critères
                ['id' => 'DESC'], // tri
                10 // limite
            );
        } else {
            // Sinon, rechercher par titre OU acronyme
            $projets = $projetRepository->createQueryBuilder('p')
                ->where('LOWER(p.titre) LIKE LOWER(:searchTerm)')
                ->orWhere('LOWER(p.acronyme) LIKE LOWER(:searchTerm)')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->orderBy('p.id', 'DESC')
                ->getQuery()
                ->getResult();
        }

        $results = array_map(function($projet) {
            return [
                'id' => $projet->getId(),
                'titre' => $projet->getTitre(),
                'acronyme' => $projet->getAcronyme(),
                'isAdmin' => $this->isGranted('ROLE_ADMIN')
            ];
        }, $projets);

        return new JsonResponse($results);
    } catch (\Exception $e) {
        // Retourner un tableau vide plutôt qu'un objet d'erreur
        return new JsonResponse([]);
    }
}

#[Route('/edit/{id}', name: 'app_projet_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): JsonResponse
{
    if ($request->isMethod('GET')) {
        return new JsonResponse([
            'id' => $projet->getId(),
            'titre' => $projet->getTitre(),
            'acronyme' => $projet->getAcronyme()
        ]);
    }

    if ($request->isMethod('POST')) {
        try {
            $projet->setTitre($request->request->get('titre'));
            $projet->setAcronyme($request->request->get('acronyme'));
            
            $entityManager->flush();
            
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Projet modifié avec succès',
                'projet' => [
                    'id' => $projet->getId(),
                    'titre' => $projet->getTitre(),
                    'acronyme' => $projet->getAcronyme()
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Erreur lors de la modification du projet'
            ], 500);
        }
    }
}








#[Route('/filter', name: 'app_projet_filter', methods: ['POST'])]
public function filter(Request $request, ProjetRepository $projetRepository): JsonResponse
{
    try {
        $filters = [
            'chef_projet' => $request->request->get('chef_projet'),
            'status' => $request->request->get('status'),
            'date_debut' => $request->request->get('date_debut'),
            'date_fin' => $request->request->get('date_fin')
        ];

        $queryBuilder = $projetRepository->createQueryBuilder('p');

        // Filtre par chef de projet
        if (!empty($filters['chef_projet'])) {
            $queryBuilder
                ->andWhere('p.user = :chef_projet')
                ->setParameter('chef_projet', $filters['chef_projet']);
        }

        // Filtre par statut
        if (!empty($filters['status'])) {
            $queryBuilder
                ->andWhere('p.statut IN (:status)')
                ->setParameter('status', $filters['status']);
        }

        // Filtre par date
        if (!empty($filters['date_debut'])) {
            $queryBuilder
                ->andWhere('p.createdAt >= :date_debut')
                ->setParameter('date_debut', new \DateTime($filters['date_debut']));
        }
        if (!empty($filters['date_fin'])) {
            $queryBuilder
                ->andWhere('p.createdAt <= :date_fin')
                ->setParameter('date_fin', new \DateTime($filters['date_fin']));
        }

        $projets = $queryBuilder
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

        $results = array_map(function($projet) {
            return [
                'id' => $projet->getId(),
                'titre' => $projet->getTitre(),
                'acronyme' => $projet->getAcronyme(),
                'isAdmin' => $this->isGranted('ROLE_ADMIN')
            ];
        }, $projets);

        return new JsonResponse($results);

    } catch (\Exception $e) {
        return new JsonResponse([
            'error' => 'Une erreur est survenue lors du filtrage',
            'message' => $e->getMessage()
        ], 500);
    }
}


#[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    if ($request->isMethod('POST')) {
        $projet = new Projet();
        $projet->setAcronyme($request->request->get('acronyme'));
        $projet->setTitre($request->request->get('titre'));
        
        // Ajout de l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Vous devez être connecté pour créer un projet'
            ], 401);
        }
        $projet->setUser($user);

        // Validation
        if (empty($projet->getAcronyme()) || empty($projet->getTitre())) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'L\'acronyme et le titre sont requis'
            ], 400);
        }

        try {
            $entityManager->persist($projet);
            $entityManager->flush();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Projet créé avec succès',
                'projet' => [
                    'id' => $projet->getId(),
                    'acronyme' => $projet->getAcronyme(),
                    'titre' => $projet->getTitre()
                ]
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Erreur lors de la création du projet: ' . $e->getMessage()
            ], 500);
        }
    }

    return new JsonResponse([
        'status' => 'error',
        'message' => 'Méthode non autorisée'
    ], 405);
}



#[Route('/details/{id}', name: 'app_projet_details', methods: ['GET'])]
public function details(Projet $projet, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'item #1 (Description)
    $item = $entityManager->getRepository(Item::class)->find(1);
    
    if (!$item) {
        throw $this->createNotFoundException('Item Description non trouvé');
    }
    
    // Récupérer les questions associées à l'item
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

    return $this->render('projet/details.html.twig', [
        'projet' => $projet,
        'item' => $item,
        'questions' => $questionsData
    ]);
}







    #[Route('/delete/{id}', name: 'app_projet_delete', methods: ['DELETE'])]
public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): JsonResponse
{
    $submittedToken = json_decode($request->getContent(), true)['_token'] ?? null;
    
    if (!$this->isCsrfTokenValid('delete', $submittedToken)) {
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Token CSRF invalide'
        ], 400);
    }

    try {
        $entityManager->remove($projet);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Projet supprimé avec succès'
        ]);
    } catch (\Exception $e) {
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Erreur lors de la suppression du projet'
        ], 500);
    }
}








}