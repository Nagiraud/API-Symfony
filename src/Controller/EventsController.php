<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use App\Form\EventFormType;
use App\Form\ModifyEventFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function events(Request $request,EntityManagerInterface $entityManager): Response
    {

        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        if ($startDate && $endDate) {
            $event = $entityManager->getRepository(Event::class)->findByDateRange(new \DateTime($startDate), new \DateTime($endDate));
        } else {
            $event = $entityManager->getRepository(Event::class)->findAll();
        }

        return $this->render('event/event.html.twig', [
            'events' => $event,
        ]);
    }

    #[Route('/event/create', name: 'app_create_event')]
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
        return $this->render('event/create.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/event/{{id}}/delete', name: 'app_delete_event')]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $events = $entityManager->getRepository(Event::class)->find($id);

        $entityManager->remove($events);
        $entityManager->flush();
        return $this->redirectToRoute('app_event');
    }


    #[Route('/event/{{id}}/modify', name: 'app_modify_event')]
    public function modify(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $events = $entityManager->getRepository(Event::class)->find($id);
        $form = $this->createForm( ModifyEventFormType::class, $events);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour l'artiste en base de données
            $entityManager->persist($events);
            $entityManager->flush();

            // Rediriger vers la liste des artistes après la modification
            return $this->redirectToRoute('app_event');
        }

        // Rendre la vue avec le formulaire
        return $this->render('event/modify.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/{{id}}', name: 'app_show_event')]
    public function info(EntityManagerInterface $entityManager, int $id): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        // Rendre la vue avec le formulaire
        return $this->render('event/individualEvent.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/event/{id}/subscribe', name: 'app_subscribe_event')]
    public function register(Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez étre connecté.');
        }

        $event->addUser($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_show_event', ['id' => $event->getId()]);
    }

    #[Route('/event/{id}/unsubscribe', name: 'app_unsubscribe_event')]
    public function unregister(Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez étre connecté.');
        }

        $event->removeUser($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_show_event', ['id' => $event->getId()]);
    }
}
