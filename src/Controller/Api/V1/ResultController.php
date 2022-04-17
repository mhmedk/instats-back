<?php

namespace App\Controller\Api\V1;

use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/results", name="api_v1_result_")
 */
class ResultController extends AbstractController
{
    // #[Route('/', name: 'index', methods: ["GET"])]
    // public function index(): Response
    // {
    //     return $this->json();
    // }

    // #[Route('/{id}', name: 'show', methods: ["GET"])]
    // public function index(): Response
    // {
    //     return $this->json();
    // }

    #[Route('/', name: 'add', methods: ["POST"])]
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validatorInterface, EntityManagerInterface $entityManager): Response
    {
        $jsonData = $request->getContent();

        // dd($jsonData);
        $result = $serializer->deserialize($jsonData, Result::class, 'json', []);

        // dd($result);

        $errors = $validatorInterface->validate($result);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        // $entityManager->refresh($result);
        $entityManager->persist($result);
        $entityManager->flush();

        return $this->json($result, 201);
    }
}
