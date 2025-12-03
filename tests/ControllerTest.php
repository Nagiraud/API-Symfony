<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\Artist;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class ControllerTest extends WebTestCase
{


    //Greetings controller (root)
    function testRootAccess() : void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }


    //Artist controller

    function testArtistCreation() : void
    {
        $client = static::createClient();
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $client->loginUser($admin);

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $crawler = $client->request('GET', '/artist/create');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();

        $form['artist[name]'] = 'test';
        $form['artist[description]'] = 'test';
        $form['artist[image]'] = 'test';

        $client->submit($form);
        $this->assertResponseRedirects('/artist');

        $artist = $entityManager->getRepository(Artist::class)->findOneBy(['name' => 'test']);

        $this->assertNotNull($artist);
    }

    function testGetArtist(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/artist');

        $this->assertResponseIsSuccessful();


        $client = static::createClient();

        $crawler = $client->request('GET', '/artist?search=test');

        $this->assertResponseIsSuccessful();
    }


    //Event controller


    //Registration controller

    //Security controller

    // User controller
}