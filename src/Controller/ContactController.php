<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use App\Form\ArtistFormType;
use App\Form\EventFormType;
use App\Form\Modify2FormType;
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
        $artists = $entityManager->getRepository(Artist::class)->find($id);

        $entityManager->remove($artists);
        $entityManager->flush();
        return $this->redirectToRoute('app_artist');
    }

    #[Route('/artist/{{id}}/modify', name: 'app_modify_artist')]
    public function modify(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $artists = $entityManager->getRepository(Artist::class)->find($id);
        $form = $this->createForm( ModifyFormType::class, $artists);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour l'artiste en base de données
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

    #[Route('/form-event', name: 'app_form-event')]
    public function event(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(type: EventFormType::class, data: $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('app_event');
        }
        return $this->render('event/form-event.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/event/{{id}}/delete2', name: 'app_delete2_event')]
    public function delete2(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $events = $entityManager->getRepository(Event::class)->find($id);

        $entityManager->remove($events);
        $entityManager->flush();
        return $this->redirectToRoute('app_event');
    }


    #[Route('/event/{{id}}/modify2', name: 'app_modify2_event')]
    public function modify2(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $events = $entityManager->getRepository(Event::class)->find($id);
        $form = $this->createForm( Modify2FormType::class, $events);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour l'artiste en base de données
            $entityManager->persist($events);
            $entityManager->flush();

            // Rediriger vers la liste des artistes après la modification
            return $this->redirectToRoute('app_event');
        }

        // Rendre la vue avec le formulaire
        return $this->render('event/modify2.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
