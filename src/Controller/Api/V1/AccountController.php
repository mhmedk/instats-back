<?php

namespace App\Controller\Api\V1;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api/v1/accounts", name="api_v1_accounts_")
 */
class AccountController extends AbstractController
{
    #[Route('/', name: 'index', methods: ["GET"])]
    public function index(AccountRepository $accountRepository): Response
    {
        $accounts = $accountRepository->findAll();
        return $this->json($accounts, 200, [], [
            'groups' => 'accounts_list',
        ]);
    }

    /**
     * Retourne un compte en fonction de son id
     * 
     * @Route("/{id}", name="show", methods={"GET"})
     * 
     * @return JsonResponse
     */
    public function show(int $id, AccountRepository $accountRepository)
    {
        // recuperation du compte en fonction de son id
        $account = $accountRepository->find($id);

        // si le compte n'existe pas renvoie une erreur 404
        if (!$account) {
            return $this->json([
                'error' => 'Le compte avec l\'id ' . $id . ' n\'existe pas'
            ], 404);
        }

        return $this->json($account, 200, [], [
            'groups' => 'account_detail'
        ]);
    }

    /**
     * Permet d'ajouter un nouveau compte
     * 
     * @Route("/", name="add", methods={"POST"})
     * 
     * @return JsonResponse
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validatorInterface)
    {
        // on récupère le JSON
        $jsonData = $request->getContent();

        // on transforme le json en objet : deserialize
        // on indique les données à transformer (desérialiser)
        // on indique le format d'arrivée
        // on indique le format de départ
        $account = $serializer->deserialize($jsonData, Account::class, 'json');

        $errors = $validatorInterface->validate($account);

        if (count($errors) > 0) {
            // code 400 : bad request
            return $this->json($errors, 400);
        }

        // on sauvegarde
        $entityManager->persist($account);
        $entityManager->flush();

        // on retourne une réponse en indiquant que la ressource a bien été créée
        return $this->json($account, 201);
    }

    /**
     * Mise à jour d'un compte en fonction de son id
     * 
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     * 
     * @return void
     */
    public function update(int $id, AccountRepository $accountRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validatorInterface)
    {
        // on récup le compte avec son id
        $account = $accountRepository->find($id);

        // on récup les donnée de la requete JSON
        $jsonData = $request->getContent();

        if (!$account) {
            return $this->json([
                'errors' => [
                    'message' => 'Le compte avec l\'id ' . $id . ' n\'existe pas'
                ]
            ], 404);
        }

        // on fusionne les données
        $accountTest = $serializer->deserialize($jsonData, Account::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $account]);
        
        $errors = $validatorInterface->validate($accountTest);

        if (count($errors) > 0) {
            // code 400 : bad request
            return $this->json($errors, 400);
        }
        
        // maj en bdd
        $entityManager->flush();

        return $this->json([
            'message' => 'Le compte \'' . $account->getName() . '\' a bien été mis à jour'
        ]);
    }

    /**
     * Permet de supprimer un compte
     * 
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * @return JsonResponse
     */
    public function delete(int $id, AccountRepository $accountRepository, EntityManagerInterface $entityManager)
    {
        $accountToDelete = $accountRepository->find($id);

        if (!$accountToDelete) {
            return $this->json([
                'errors' => [
                    'message' => 'Le compte avec l\'id ' . $id . ' n\'existe pas'
                ]
                ], 404);
        }

        $entityManager->remove($accountToDelete);
        $entityManager->flush();

        // on retourne une réponse en indiquant que la ressource a bien été supprimée
        return $this->json([
            'message' => 'Le compte avec l\'id ' . $id . ' a bien été supprimé'
        ]);
    }
}
