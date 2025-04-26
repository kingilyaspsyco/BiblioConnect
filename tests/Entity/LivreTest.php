<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Livre;
use App\Entity\Auteur;

class LivreTest extends TestCase
{
    public function testLivreCreationAvecAuteur(): void
    {
        $livre = new Livre();
        $livre->setTitre('Le Petit Prince');

        $auteur = new Auteur();
        $auteur->setNom('Antoine de Saint-Exupéry');

        $livre->setAuteur($auteur);

        $this->assertEquals('Le Petit Prince', $livre->getTitre());
        $this->assertEquals('Antoine de Saint-Exupéry', $livre->getAuteur()->getNom());
    }
}
