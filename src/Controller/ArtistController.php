<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artist')]
    public function movies(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Artist::class);
        $artist = $repository->findAll();

        return $this->render('artist/artist.html.twig', [
            'artists' => $artist,
        ]);
    }
}
