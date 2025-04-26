<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Auteur;
use App\Form\AuteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;



class AuteurController extends AbstractController
{
#[Route('/auteurs', name: 'app_auteurs')]
public function index(AuteurRepository $auteurRepository): Response
{
    return $this->render('auteur/index.html.twig', [
        'auteurs' => $auteurRepository->findAll(),
    ]);
}

#[Route('/auteurs/new', name: 'app_auteurs_new')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
public function new(Request $request, EntityManagerInterface $em): Response
{
    $auteur = new Auteur();
    $form = $this->createForm(AuteurType::class, $auteur);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($auteur);
        $em->flush();

        return $this->redirectToRoute('app_auteurs');
    }

    return $this->render('auteur/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/auteurs/search', name: 'app_auteur_search')]
public function search(Request $request, AuteurRepository $auteurRepository): Response
{
    $query = $request->query->get('q');
    if ($query) {
        $auteurs = $auteurRepository->createQueryBuilder('a')
            ->where('a.nom LIKE :q OR a.prenom LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    } else {
        $auteurs = $auteurRepository->findAll();
    }

    return $this->render('auteur/_auteurs_list.html.twig', [
        'auteurs' => $auteurs,
    ]);
}


#[Route('/auteurs/{id}/edit', name: 'app_auteurs_edit')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
public function edit(Auteur $auteur, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(AuteurType::class, $auteur);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();

        return $this->redirectToRoute('app_auteurs');
    }

    return $this->render('auteur/edit.html.twig', [
        'form' => $form->createView(),
        'auteur' => $auteur,
    ]);
}

#[Route('/auteurs/{id}/delete', name: 'app_auteurs_delete')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
public function delete(Auteur $auteur, EntityManagerInterface $em): Response
{
    $em->remove($auteur);
    $em->flush();

    return $this->redirectToRoute('app_auteurs');
}

}
