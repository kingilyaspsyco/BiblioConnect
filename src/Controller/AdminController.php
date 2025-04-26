<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\UserRoleType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LivreRepository;
use App\Repository\ReservationRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commentaire;
use App\Entity\Livre;

final class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(LivreRepository $livreRepo, ReservationRepository $reservationRepo): Response
    {
        $livresDisponibles = $livreRepo->findBy(['disponible' => true]);
        $livresReserves = [];

        foreach ($livreRepo->findBy(['disponible' => false]) as $livre) {
            foreach ($livre->getReservations() as $reservation) {
                if ($reservation->getStatut() === 'confirmee') {
                    $livresReserves[] = $livre;
                    break;
                }
            }
        }


        $reservationsEnAttente = $reservationRepo->findBy(['statut' => 'en_attente']);

        return $this->render('admin/dashboard.html.twig', [
            'livresDisponibles' => $livresDisponibles,
            'livresReserves' => $livresReserves,
            'reservationsEnAttente' => $reservationsEnAttente,
        ]);
    }
    #[Route('/admin/reservation/{id}/confirmer', name: 'admin_confirmer_reservation')]
    public function confirmerReservation(int $id, ReservationRepository $repo, EntityManagerInterface $em): Response
    {
        $reservation = $repo->find($id);

        if ($reservation) {
            $reservation->setStatut('confirmee');
            $em->flush();
        }

        return $this->redirectToRoute('admin_dashboard');
    }
    #[Route('/admin/reservation/{id}/decliner', name: 'admin_decliner_reservation')]
    public function declinerReservation(int $id, ReservationRepository $repo, EntityManagerInterface $em): Response
    {
        $reservation = $repo->find($id);

        if ($reservation) {
            $livre = $reservation->getLivre();
            $livre->setDisponible(true);
            $em->remove($reservation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/commentaire/{id}/supprimer', name: 'admin_commentaire_delete')]
    public function delete(Commentaire $commentaire, EntityManagerInterface $em): Response
    {
        $livre = $commentaire->getLivre();
        $em->remove($commentaire);
        $em->flush();

        $this->addFlash('danger', 'Commentaire supprimé.');
        return $this->redirectToRoute('admin_livre_commentaires', ['id' => $livre->getId()]);
    }


    #[Route('/admin/livre/{id}/commentaires', name: 'admin_livre_commentaires')]
    public function adminVoirCommentaires(Livre $livre, CommentaireRepository $commentaireRepo): Response
    {
        $commentaires = $commentaireRepo->findBy(['livre' => $livre]);

        $totalNotes = 0;
        $nombreCommentaires = count($commentaires);

        foreach ($commentaires as $commentaire) {
            $totalNotes += $commentaire->getNote();
        }

        $moyenne = $nombreCommentaires > 0 ? round($totalNotes / $nombreCommentaires, 1) : null;

        return $this->render('admin/commentaires_livre.html.twig', [
            'livre' => $livre,
            'commentaires' => $commentaires,
            'moyenne' => $moyenne,
        ]);
    }
    #[Route('/admin/utilisateurs', name: 'admin_users')]
    public function listUsers(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();

        return $this->render('admin/users_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/utilisateur/{id}/edit-role', name: 'admin_user_edit_role')]
    public function editUserRole(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedRole = $form->get('roles')->getData();
            $user->setRoles([$selectedRole]); 
            $em->flush();
            $this->addFlash('success', 'Rôle mis à jour.');
            return $this->redirectToRoute('admin_users');
        }     
        return $this->render('admin/edit_user_role.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    #[Route('/admin/utilisateur/{id}/delete', name: 'admin_user_delete')]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('danger', 'Tu ne peux pas te supprimer toi-même.');
            return $this->redirectToRoute('admin_users');
        }        
        $em->remove($user);
        $em->flush();

        $this->addFlash('danger', 'Utilisateur supprimé.');

        return $this->redirectToRoute('admin_users');
    }

}
