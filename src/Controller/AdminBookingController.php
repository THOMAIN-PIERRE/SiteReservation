<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Service\StatsService;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination, StatsService $statsService)
    {
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page);

        $stats = $statsService->getStats();

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats,
        ]);
    }

    /**
     * Permet d'éditer une réservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager, StatsService $statsService)
    {

        $user = $this->getUser()->getId();

        $stats = $statsService->getStats();

        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $booking->setAmount(0);
            $booking->setReviser($user);
            $booking->setUpdatedAt(new \DateTime());

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n° {$booking->getId()} a bien été modifiée"
            );
            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking,
            'stats' => $stats,
        ]);
    }

    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     *
     * @return Response
     */
    public function delete(Booking $booking,  EntityManagerInterface $manager){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée "
        );
        return $this->redirectToRoute("admin_booking_index");
    }
}

