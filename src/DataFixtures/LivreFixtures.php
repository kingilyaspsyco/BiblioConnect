<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Langue; // <-- n'oublie pas d'importer l'entité Langue
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créer 5 catégories
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($faker->word);
            $manager->persist($categorie);
            $categories[] = $categorie;
        }

        // Créer 5 auteurs
        $auteurs = [];
        for ($i = 0; $i < 5; $i++) {
            $auteur = new Auteur();
            $auteur->setNom($faker->lastName);
            $auteur->setPrenom($faker->firstName);
            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }

        // Créer 3 langues
        $langues = [];
        foreach (['Français', 'Anglais', 'Espagnol'] as $nomLangue) {
            $langue = new Langue();
            $langue->setNom($nomLangue);
            $manager->persist($langue);
            $langues[] = $langue;
        }

        // Créer 20 livres
        for ($i = 0; $i < 20; $i++) {
            $livre = new Livre();
            $livre->setTitre($faker->sentence(3));
            $livre->setAuteur($faker->randomElement($auteurs));
            $livre->setCategorie($faker->randomElement($categories));
            $livre->setLangue($faker->randomElement($langues)); // associer une entité Langue
            $livre->setDateajout($faker->dateTimeBetween('-1 year', 'now'));
            $livre->setDisponible($faker->boolean(80));

            $manager->persist($livre);
        }

        $manager->flush();
    }
}
