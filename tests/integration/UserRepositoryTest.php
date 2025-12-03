<?php

namespace App\Tests\integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        // Démarre le kernel Symfony pour avoir accès aux services
        $kernel = self::bootKernel();

        // Récupère l'EntityManager depuis le container
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {

        // Ferme l'EntityManager pour éviter les fuites mémoire
        $this->entityManager->close();
    }

    // TEST 1: Test de création et persistance d'un utilisateur
    public function testUserCreationAndPersistence(): void
    {
        // Création d'un nouvel utilisateur
        $user = new User();
        $user->setUsername('test_user');
        $user->setPassword('hashed_password_123');
        $user->setRoles(['ROLE_USER']);

        // Persistance en base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Vérification que l'ID a été généré
        $this->assertNotNull($user->getId());
        $this->assertIsInt($user->getId());

        // Récupération depuis la base pour vérifier la persistance
        $userRepository = $this->entityManager->getRepository(User::class);
        $retrievedUser = $userRepository->find($user->getId());

        // Assertions
        $this->assertNotNull($retrievedUser);
        $this->assertEquals('test_user', $retrievedUser->getUsername());
        $this->assertEquals('test_user', $retrievedUser->getUserIdentifier());
        $this->assertEquals('hashed_password_123', $retrievedUser->getPassword());

        // Vérification des rôles
        $roles = $retrievedUser->getRoles();
        $this->assertContains('ROLE_USER', $roles);
        $this->assertCount(1, $roles); // ROLE_USER

        // Nettoyage
        $this->entityManager->remove($retrievedUser);
        $this->entityManager->flush();
    }
}