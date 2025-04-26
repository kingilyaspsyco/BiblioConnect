<?php

namespace App\Controller;

use App\Repository\LangueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Langue;
use App\Form\LangueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LangueController extends AbstractController
{
    #[Route('/langues', name: 'app_langues')]
    public function index(LangueRepository $langueRepository): Response
    {
        $langues = $langueRepository->findAll();

        return $this->render('langue/index.html.twig', [
            'langues' => $langues,
        ]);
    }

    #[Route('/langues/new', name: 'app_langues_new')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $langue = new Langue();
        $form = $this->createForm(LangueType::class, $langue);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($langue);
            $em->flush();

            $this->addFlash('success', 'Langue ajoutée avec succès.');

            return $this->redirectToRoute('app_langues');
        }

        return $this->render('langue/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/langues/{id}/edit', name: 'app_langues_edit')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function edit(Langue $langue, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(LangueType::class, $langue);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Langue modifiée avec succès.');

            return $this->redirectToRoute('app_langues');
        }

        return $this->render('langue/edit.html.twig', [
            'form' => $form->createView(),
            'langue' => $langue,
        ]);
    }

    #[Route('/langues/{id}/delete', name: 'app_langues_delete')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function delete(Langue $langue, EntityManagerInterface $em): Response
    {
        $em->remove($langue);
        $em->flush();

        $this->addFlash('success', 'Langue supprimée avec succès.');

        return $this->redirectToRoute('app_langues');
    }
    #[Route('/langues/search', name: 'app_langues_search')]
    public function search(Request $request, LangueRepository $langueRepository): Response
    {
        $query = $request->query->get('q');
        if ($query) {
            $langues = $langueRepository->createQueryBuilder('l')
                ->where('l.nom LIKE :q')
                ->setParameter('q', '%' . $query . '%')
                ->getQuery()
                ->getResult();
        } else {
            $langues = $langueRepository->findAll();
        }

        return $this->render('langue/_langues_list.html.twig', [
            'langues' => $langues,
        ]);
    }

}