<?php
namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class LoginAndLivreTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        // Crée l'utilisateur s'il n'existe pas
        $userRepository = $this->entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneByEmail('testuser@example.com');

        if (!$existingUser) {
            $user = new User();
            $user->setEmail('testuser@example.com');
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
            $user->setFirstname('Test');
            $user->setLastname('User');
            $user->setRoles(['ROLE_USER']);
            $user->setIsverified(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function testLoginAndAddLivre(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneByEmail('testuser@example.com');
        $this->assertNotNull($user, 'Utilisateur testuser@example.com introuvable.');

        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', '/livres/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'livre[titre]' => 'Nouveau Livre Auto Test',
            'livre[description]' => 'Test description',
            'livre[disponible]' => true,
            'livre[dateajout]' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/livres/');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('body', 'Nouveau Livre Auto Test');
    }
}
