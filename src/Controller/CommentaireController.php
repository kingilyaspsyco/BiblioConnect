<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Livre;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    #[Route('/livre/{livreId}/commenter', name: 'app_commentaire_new')]
    public function newCommentaire(
        int $livreId,
        LivreRepository $livreRepo,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $livre = $livreRepo->find($livreId);

        if (!$livre) {
            throw $this->createNotFoundException('Livre non trouvé.');
        }

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setUser($this->getUser());
            $commentaire->setLivre($livre);
            $commentaire->setCreatedAt(new \DateTimeImmutable());
            $commentaire->setIsApproved(true); // Auto-validation

            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Merci pour votre commentaire !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('commentaire/new.html.twig', [
            'form' => $form->createView(),
            'livre' => $livre,
        ]);
    }

    #[Route('/livres/{id}/commentaires', name: 'livre_commentaires')]
    public function voirCommentaires(Livre $livre, CommentaireRepository $commentaireRepo): Response
    {
        // Seuls les commentaires approuvés sont visibles par les utilisateurs
        $commentaires = $commentaireRepo->findBy(['livre' => $livre, 'isApproved' => true]);

        $totalNotes = 0;
        $nombreCommentaires = count($commentaires);

        foreach ($commentaires as $commentaire) {
            $totalNotes += $commentaire->getNote();
        }

        $moyenne = $nombreCommentaires > 0 ? round($totalNotes / $nombreCommentaires, 1) : null;

        return $this->render('commentaire/commentaires.html.twig', [
            'livre' => $livre,
            'commentaires' => $commentaires,
            'moyenne' => $moyenne,
        ]);
    }


    // Vue admin des commentaires
    #[Route('/admin/livre/{id}/commentaires', name: 'admin_livre_commentaires')]
    public function adminVoirCommentaires(Livre $livre, CommentaireRepository $commentaireRepo): Response
    {
        $commentaires = $commentaireRepo->findBy(['livre' => $livre]);

        $totalNotes = array_sum(array_map(fn($c) => $c->getNote(), $commentaires));
        $nombreCommentaires = count($commentaires);
        $moyenne = $nombreCommentaires > 0 ? round($totalNotes / $nombreCommentaires, 1) : null;

        return $this->render('admin/commentaires_livre.html.twig', [
            'livre' => $livre,
            'commentaires' => $commentaires,
            'moyenne' => $moyenne,
        ]);
    }
}
