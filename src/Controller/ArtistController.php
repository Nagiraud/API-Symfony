<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistFormType;
use App\Form\ModifyArtistFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/artist/{{id}}/delete', name: 'app_delete_artist')]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $artists = $entityManager->getRepository(Artist::class)->find($id);

        $entityManager->remove($artists);
        $entityManager->flush();
        return $this->redirectToRoute('app_artist');
    }

    #[Route('/artist/{{id}}/modify', name: 'app_modify_artist')]
    public function modify(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,int $id): Response
    {

        $artists = $entityManager->getRepository(Artist::class)->find($id);
        $form = $this->createForm( ModifyArtistFormType::class, $artists);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );

                $artists->setImage($newFilename);
            }

            $entityManager->persist($artists);
            $entityManager->flush();

            // Rediriger vers la liste des artistes après la modification
            return $this->redirectToRoute('app_artist');
        }

        // Rendre la vue avec le formulaire
        return $this->render('artist/modify.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/artist/{{id}}', name: 'app_show_artist')]
    public function artist(EntityManagerInterface $entityManager,int $id): Response
    {
        $repository = $entityManager->getRepository(Artist::class);
        $artist = $repository->find($id);

        return $this->render('artist/individualArtist.html.twig', [
            'artist' => $artist,
        ]);
    }


    #[Route("/artist/create", name:"app_create_artist")]
    public function Create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistFormType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
                

                $artist->setImage($newFilename);
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('app_artist');
        }

        return $this->render('artist/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
