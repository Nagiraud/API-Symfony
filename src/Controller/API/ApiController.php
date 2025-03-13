<?php

namespace App\Controller\API;

use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiController extends AbstractController
{
    #[Route('/api/v1/artist', name: 'app_api_artist', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {

        $artists = $entityManager->getRepository(Artist::class)->findAll();
        $artistsList = $serializer->serialize($artists, 'json',['groups' => 'artist']);
        return new JsonResponse($artistsList, Response::HTTP_OK, [], true);
    }
}
