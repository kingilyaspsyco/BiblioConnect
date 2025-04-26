<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LivreRepository;
use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\CommentaireRepository;

final class ReservationController extends AbstractController
{
    #[Route('/reservation/{livreId}', name: 'app_reservation_create')]
    public function reserver(int $livreId, EntityManagerInterface $em, LivreRepository $livreRepo): Response
    {
        $user = $this->getUser();
        $livre = $livreRepo->find($livreId);

        if (!$livre || !$user || !$livre->isDisponible()) {
            return $this->redirectToRoute('app_livre');
        }

        $reservation = new Reservation();
        $reservation->setLivre($livre);
        $reservation->setUser($user);
        $reservation->setDatereservation(new \DateTime());
        $reservation->setStatut('en_attente'); 

        $livre->setDisponible(false);

        $em->persist($reservation);
        $em->flush();


        return $this->redirectToRoute('app_profile');
    }

    #[Route('/reservation/{id}/return', name: 'app_reservation_return')]
    public function returnBook(int $id, ReservationRepository $reservationRepo, CommentaireRepository $commentRepo, EntityManagerInterface $em): Response
    {
        $reservation = $reservationRepo->find($id);

        if (!$reservation || $reservation->getStatut() !== 'confirmee') {
            $this->addFlash('danger', 'Réservation invalide.');
            return $this->redirectToRoute('app_profile');
        }

        $livre = $reservation->getLivre();
        $livre->setDisponible(true); 

        $em->remove($reservation);

        $commentaires = $commentRepo->findBy(['livre' => $livre]);
        if (count($commentaires) > 0) {
            $totalNotes = array_sum(array_map(fn($c) => $c->getNote(), $commentaires));
            $moyenne = $totalNotes / count($commentaires);
            $livre->setNoteMoyenne(round($moyenne, 2));
        } else {
            $livre->setNoteMoyenne(null);
        }

        $em->flush();

        $this->addFlash('success', 'Merci pour le retour du livre.');

        return $this->redirectToRoute('app_profile');
    }



}
