<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\ReservationRepository;

#[Route('/librarian')]
#[IsGranted('ROLE_LIBRARIAN')]
class LibrarianController extends AbstractController
{
    #[Route('/dashboard', name: 'librarian_dashboard')]
    public function dashboard(LivreRepository $livreRepo, ReservationRepository $reservationRepo): Response
    {
        $livres = $livreRepo->findAll();
        $reservations = $reservationRepo->findAll();

        return $this->render('librarian/dashboard.html.twig', [
            'livres' => $livres,
            'reservations' => $reservations,
        ]);
    }
}