<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistFormType;
use App\Form\ModifyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Artist();
        $form = $this->createForm(type: ArtistFormType::class, data: $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
            return $this->redirectToRoute('app_artist');
        }
        return $this->render('artist/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/artist/{{id}}/delete', name: 'app_delete_artist')]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $artist = $entityManager->getRepository(Artist::class)->find($id);

        $entityManager->remove($artist);
        $entityManager->flush();
        return $this->redirectToRoute('app_artist');
    }

    #[Route('/artist/{{id}}/modify', name: 'app_modify_artist')]
    public function modify(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $artist = $entityManager->getRepository(Artist::class)->find($id);
        $form = $this->createForm( ModifyFormType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour l'artiste en base de données
            $entityManager->persist($artist);
            $entityManager->flush();

            // Rediriger vers la liste des artistes après la modification
            return $this->redirectToRoute('app_artist');
        }

        // Rendre la vue avec le formulaire
        return $this->render('artist/modify.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
