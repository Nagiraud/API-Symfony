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

}