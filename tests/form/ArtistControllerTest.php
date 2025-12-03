<?php

namespace App\Tests\Controller;

use App\Controller\ArtistController;
use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtistControllerTest extends TestCase
{
    public function testMoviesWithSearch()
    {
        // 1) Simuler une requête avec ?search=John
        $request = new Request(['search' => 'John']);

        // 2) Mock du repository
        $repositoryMock = $this->createMock(\App\Repository\ArtistRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findByName')
            ->with('John')
            ->willReturn(['artist1', 'artist2']);

        // 3) Mock de l’EntityManager
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')
            ->with(Artist::class)
            ->willReturn($repositoryMock);

        // 4) Mock du contrôleur avec la méthode render()
        $controller = $this->getMockBuilder(ArtistController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['render'])
            ->getMock();

        $controller->method('render')
            ->willReturn(new Response('rendered template'));

        // 5) Appel de la méthode à tester
        $response = $controller->movies($request, $entityManagerMock);

        // 6) Vérification
        $this->assertSame('rendered template', $response->getContent());
    }


    public function testArtist()
    {
        // 1) ID d’artiste à tester
        $id = 5;

        // 2) On simule l’artiste trouvé en base
        $fakeArtist = (object) ['name' => 'John Doe'];

        // 3) Mock du repository
        $repositoryMock = $this->createMock(\App\Repository\ArtistRepository::class);
        $repositoryMock->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($fakeArtist);

        // 4) Mock de l’EntityManager
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')
            ->willReturn($repositoryMock);

        // 5) Mock du contrôleur + mock de la méthode render()
        $controller = $this->getMockBuilder(\App\Controller\ArtistController::class)
            ->onlyMethods(['render'])
            ->getMock();

        // → Quand render est appelé, il doit retourner une Response
        $controller->method('render')
            ->willReturn(new \Symfony\Component\HttpFoundation\Response('rendered template'));

        // 6) On appelle la méthode à tester
        $response = $controller->artist($entityManagerMock, $id);

        // 7) Vérification
        $this->assertEquals('rendered template', $response->getContent());
    }



}


