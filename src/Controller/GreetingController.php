<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GreetingController extends AbstractController
{
    #[Route('/api/artist', name: 'api_artist', methods: ['GET'])]
    public function getProducts(EntityManagerInterface $entityManager): JsonResponse
    {
        $repository = $entityManager->getRepository(Artist::class);
        $movie = $repository->findAll();

        $data = array_map(fn($movie) => [
            'id' => $movie->getId(),
            'name' => $movie->getName(),
            'description' => $movie->getDescription(),
        ], $movie);

        return $this->json($data);
    }
}