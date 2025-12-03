<?php

namespace App\Tests\Controller;

use App\Controller\EventsController;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventsControllerTest extends TestCase
{
    public function testEventsWithDateRange()
    {
        // Requête simulée
        $request = new Request([
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
        ]);

        // Faux résultats d'événements
        $fakeEvents = ['event1', 'event2'];

        // Mock du repository
        $repositoryMock = $this->createMock(EventRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findByDateRange')
            ->with(
                new \DateTime('2024-01-01'),
                new \DateTime('2024-01-31')
            )
            ->willReturn($fakeEvents);

        // Mock EntityManager
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')
            ->willReturn($repositoryMock);

        // Mock du controller + mock de la méthode render()
        $controller = $this->getMockBuilder(EventsController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->method('render')
            ->willReturn(new Response('rendered template'));

        // Execution
        $response = $controller->events($request, $entityManagerMock);

        // Vérification
        $this->assertEquals('rendered template', $response->getContent());
    }


    // Cas normal quand l'utilisateur est connecter
    public function testRegisterWithLoggedUser()
    {
        // 1) Fake event
        $event = $this->createMock(\App\Entity\Event::class);
        $event->method('getId')->willReturn(10);

        // On veut vérifier que addUser() est bien appelé une fois
        $event->expects($this->once())
            ->method('addUser');

        // 2) Fake user
        $fakeUser = (object)['username' => 'demo'];

        // 3) Mock EntityManager → flush() doit être appelé une fois
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->once())
            ->method('flush');

        // 4) Mock du controller
        // On doit mocker getUser() + redirectToRoute()
        $controller = $this->getMockBuilder(\App\Controller\EventsController::class)
            ->onlyMethods(['getUser', 'redirectToRoute'])
            ->getMock();

        // getUser() doit renvoyer notre faux utilisateur
        $controller->method('getUser')
            ->willReturn($fakeUser);

        // redirectToRoute() doit renvoyer une vraie Response
        $controller->method('redirectToRoute')
            ->with('app_show_event', ['id' => 10])
            ->willReturn(new \Symfony\Component\HttpFoundation\Response('redirect ok'));

        // 5) Exécution
        $response = $controller->register($event, $entityManagerMock);

        // 6) Vérif
        $this->assertEquals('redirect ok', $response->getContent());
    }


}