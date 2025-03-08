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
    #[Route('/users', name: 'app_users')]
    public function users(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Artist::class);
        $movies = $repository->findAll();

        return $this->render('artist/artist.html.twig', [
            'name' => $movies,
        ]);
    }

    #[Route('/users/{id}', name: 'app_users_show', requirements: ['id' => '\d+'])]
    public function showUser(int $id,EntityManagerInterface $entityManager): Response
    {
        return $this->render('movies/show.html.twig');
    }


    #[Route('/event/{id}', name: 'app_event_show', requirements: ['id' => '\d+'])]
    public function showEvent(int $id,EntityManagerInterface $entityManager): Response
    {
        return $this->render('movies/show.html.twig');
    }

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