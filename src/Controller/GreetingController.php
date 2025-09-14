<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GreetingController extends AbstractController
{
    #[Route('/', name: 'app_hello')]
    public function hello(EntityManagerInterface $entityManager): Response
    {

        $event = $entityManager->getRepository(Event::class)->findAll();
        $artist = $entityManager->getRepository(Artist::class)->findAll();

        $user = $this->getUser();
        if ($user) {
            $eventsUser = $user->getEvents();
        } else {
            $eventsUser = [];
        }

        return $this->render('greeting/hello.html.twig',[
            "name"=> $this->getUser() ? $this->getUser()->getUserIdentifier() : "user",
            "eventsUser" => $eventsUser,
            "events" => $event,
            "artists" => $artist,
        ]);
    }
}