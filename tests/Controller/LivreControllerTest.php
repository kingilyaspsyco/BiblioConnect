<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LivreControllerTest extends WebTestCase
{
    public function testLivresPageAccessible(): void
{
    $client = static::createClient();
    $crawler = $client->request('GET', '/livres');

    // Suivre automatiquement la redirection (301 → 200)
    $this->assertResponseRedirects('/livres/', 301);
    $client->followRedirect();

    $this->assertResponseIsSuccessful(); // maintenant c'est 200 OK
    $this->assertSelectorExists('h1'); // Exemple : vérifier qu’il y a un <h1> sur la page
}

}
