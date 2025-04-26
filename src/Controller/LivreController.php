<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;

#[Route('/livres')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_livre')]
    public function index(Request $request, LivreRepository $livreRepo, CommentaireRepository $commentaireRepo): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $livres = $livreRepo->findAll();
        } elseif ($this->isGranted('ROLE_LIBRARIAN')) {
            $livres = $livreRepo->findAll();
        } else {
            $livres = $livreRepo->findBy(['disponible' => true]);
        }

        $livresAvecNotes = $this->getLivresAvecNotes($livres, $commentaireRepo);

        return $this->render('livre/index.html.twig', [
            'livresAvecNotes' => $livresAvecNotes,
        ]);
    }

    #[Route('/search', name: 'app_livre_search', methods: ['GET'])]
    public function search(Request $request, LivreRepository $livreRepo, CommentaireRepository $commentaireRepo): Response
    {
        $query = $request->query->get('q', '');

        $qb = $livreRepo->createQueryBuilder('l')
            ->leftJoin('l.auteur', 'a')
            ->leftJoin('l.categorie', 'c')
            ->leftJoin('l.langue', 'la')
            ->where('l.titre LIKE :q OR a.nom LIKE :q OR a.prenom LIKE :q OR c.nom LIKE :q OR la.nom LIKE :q')
            ->setParameter('q', '%' . $query . '%');

        if (!$this->isGranted('ROLE_ADMIN')) {
            $qb->andWhere('l.disponible = true');
        }

        $livres = $qb->getQuery()->getResult();

        $livresAvecNotes = $this->getLivresAvecNotes($livres, $commentaireRepo);

        return $this->render('livre/_livres_list.html.twig', [
            'livresAvecNotes' => $livresAvecNotes,
        ]);
    }

    #[Route('/new', name: 'app_livre_new')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livre->setDisponible(true);
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('cover_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gère les erreurs d'upload
                }

                $livre->setImageCouverture($newFilename);
            }

            $em->persist($livre);
            $em->flush();

            return $this->redirectToRoute('app_livre');
        }

        return $this->render('livre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livre_edit')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function edit(Livre $livre, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('cover_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_livre');
                }

                $livre->setImageCouverture($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('app_livre');
        }

        return $this->render('livre/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_livre_delete')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_LIBRARIAN')")
     */
    public function delete(Livre $livre, EntityManagerInterface $em): Response
    {
        $em->remove($livre);
        $em->flush();

        return $this->redirectToRoute('app_livre');
    }

    #[Route('/livre/{id}', name: 'app_livre_show')]
    public function show(Livre $livre, Request $request, EntityManagerInterface $em): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setUser($this->getUser());
            $commentaire->setLivre($livre);
            $commentaire->setCreatedAt(new \DateTimeImmutable());

            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Commentaire ajouté !');
            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
        }

        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
            'commentaires' => $livre->getCommentaires(),
        ]);
    }

    #[Route('/{id}/commentaires', name: 'app_livre_commentaires')]
    public function voirCommentaires(Livre $livre, CommentaireRepository $commentaireRepo): Response
    {
        $commentaires = $commentaireRepo->findBy(['livre' => $livre, 'isApproved' => true]);

        return $this->render('commentaire/commentaires.html.twig', [
            'livre' => $livre,
            'commentaires' => $commentaires,
        ]);
    }

    private function getLivresAvecNotes(array $livres, CommentaireRepository $commentaireRepo): array
    {
        $livresAvecNotes = [];

        foreach ($livres as $livre) {
            $commentaires = $commentaireRepo->findBy(['livre' => $livre, 'isApproved' => true]);

            $totalNotes = 0;
            $nombreCommentaires = count($commentaires);

            foreach ($commentaires as $commentaire) {
                $totalNotes += $commentaire->getNote();
            }

            $moyenne = $nombreCommentaires > 0 ? round($totalNotes / $nombreCommentaires, 1) : null;

            $livresAvecNotes[] = [
                'livre' => $livre,
                'noteMoyenne' => $moyenne,
            ];
        }

        return $livresAvecNotes;
    }
}
