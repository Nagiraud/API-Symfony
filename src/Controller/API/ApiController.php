<?php

namespace App\Controller\API;

use OpenApi\Annotations as OA;
use App\Entity\Artist;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiController extends AbstractController
{
    #[Route('/api/v1/artist', name: 'app_api_artist', methods: ['GET'])]
    public function ApiArtist(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {

        $artists = $entityManager->getRepository(Artist::class)->findAll();
        $artistsList = $serializer->serialize($artists, 'json', [
            'groups' => ['artist']
        ]);
        return new JsonResponse($artistsList, 200, [], true);
    }

    #[Route('/api/v1/artist/{id}', name: 'app_api_show_artist', methods: ['GET'])]
    public function ApiShowArtist(EntityManagerInterface $entityManager, SerializerInterface $serializer,int $id): JsonResponse
    {

        $artists = $entityManager->getRepository(Artist::class)->find($id);
        $artistsList = $serializer->serialize($artists, 'json', [
            'groups' => ['artist']
        ]);
        return new JsonResponse($artistsList, 200, [], true);
    }

    #[Route('/api/v1/event', name: 'app_api_event', methods: ['GET'])]
    public function ApiEvent(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {

        $events = $entityManager->getRepository(Event::class)->findAll();
        $artistsList = $serializer->serialize($events, 'json', [
            'groups' => ['event']
        ]);
        return new JsonResponse($artistsList, 200, [], true);
    }

    #[Route('/api/v1/event/{id}', name: 'app_api_show_event', methods: ['GET'])]
    public function ApiShowEvent(EntityManagerInterface $entityManager, SerializerInterface $serializer,int $id): JsonResponse
    {

        $events = $entityManager->getRepository(Event::class)->find($id);
        $artistsList = $serializer->serialize($events, 'json', [
            'groups' => ['event']
        ]);
        return new JsonResponse($artistsList, 200, [], true);
    }
}
