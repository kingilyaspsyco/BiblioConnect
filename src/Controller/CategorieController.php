<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategorieController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/new', name: 'app_categories_new')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie créée avec succès.');

            $redirectUrl = $request->query->get('_target_path', $this->generateUrl('app_categories'));
            return $this->redirect($redirectUrl);
        }

        return $this->render('categorie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/categories/{id}/edit', name: 'app_categories_edit')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès.');

            $targetPath = $request->request->get('_target_path') ?? $this->generateUrl('app_categories');
            return $this->redirect($targetPath);
        }

        return $this->render('categorie/edit.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie,
            '_target_path' => $request->headers->get('referer'), // pour revenir à la page précédente
        ]);
    }


    #[Route('/categories/{id}/delete', name: 'app_categories_delete')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function delete(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès.');

        $referer = $request->headers->get('referer') ?? $this->generateUrl('app_categories');
        return $this->redirect($referer);
    }
    #[Route('/categories/search', name: 'app_categories_search')]
    public function search(Request $request, CategorieRepository $categorieRepository): Response
    {
        $query = $request->query->get('q');
        if ($query) {
            $categories = $categorieRepository->createQueryBuilder('c')
                ->where('c.nom LIKE :q')
                ->setParameter('q', '%' . $query . '%')
                ->getQuery()
                ->getResult();
        } else {
            $categories = $categorieRepository->findAll();
        }

        return $this->render('categorie/_categories_list.html.twig', [
            'categories' => $categories,
        ]);
    }


}
