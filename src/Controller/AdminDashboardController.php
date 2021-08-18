<?php

namespace App\Controller;

use App\Service\StatsService;
use App\Repository\BookingRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {

        $stats      = $statsService->getStats();

        $bestAds    = $statsService->getAdsStats('DESC');

        $worstAds   = $statsService->getAdsStats('ASC');

        $todayBookings = $statsService->todayBookings('ASC');


        



        // $todayBookingsAmount = $statsService->todayBookingsAmount();
        
        return $this->render('admin/dashboard/index.html.twig', [
            'stats'     => $stats,
            'bestAds'   => $bestAds,
            'worstAds'  => $worstAds,
            'todayBookings' => $todayBookings,
            // 'bookingsAmount' => $bookingsAmount,
            // 'todayBookingsAmount' => $todayBookingsAmount
        ]);
    }


    /**
     * @Route("/stats", name="admin_stats")
     */
    public function statistiques(CategoryRepository $categoryRepository, BookingRepository $bookingRepository, StatsService $statsService)
    {

        $stats = $statsService->getStats();

        // On va chercher toutes les catégories
        $categories = $categoryRepository->findAll();

        // On apporte les infos dont a besoin mon graphique
        $categoryName = [];
        $categoryColor = [];
        $categoryCount = [];

        // Je vais boucler sur toutes mes categories et j'appelle category
        foreach($categories as $category){
            // Puis je rempli chacune des variables avec la catégorie actuelle 
            $categoryName[] = $category->getName();
            $categoryColor[] = $category->getColor();
            $categoryCount[] = count($category->getAds()); // count() permet de calculer la taille d'un objet

        }

        // On va chercher le nombre de commandes passées par date
        // $commandes = $orderRepository-> countByDate();
        $bookings = $bookingRepository-> countByDate();

        // dd($bookings);

        // On doit là aussi préparer nos données pour les séparer tel qu'attendu par chartJS
        $dates = [];
        $bookingsCount = [];

        foreach($bookings as $booking){
            $dates[] = $booking['dateBooking'];
            $bookingsCount[] = $booking['booked'];
        }


        // On va chercher le nombre de commandes passées par date
        // $commandes = $orderRepository-> countByDate();
        // $commandes = $orderRepository-> countByDate();

        // dd($commandes);

        // On doit là aussi préparer nos données pour les séparer tel qu'attendu par chartJS
        // $dates = [];
        // $commandesCount = [];

        // foreach($commandes as $commande){
        //     $dates[] = $commande['dateCommande'];
        //     $commandesCount[] = $commande['convertedOrders'];
        // }



        // $todayBookings = $statsService->todayBookings('DESC');
       

        return $this->render('admin/dashboard/stats.html.twig', [
            //Toutes ces infos que j'ai mises en forme pour mon graphique je vais devoir les envoyer à mon TWIG en JSON !
            // (parce que dans la doc JS CHART ce sont de tableau JSON qui sont demandés)
            'categoryName' => json_encode($categoryName),
            'categoryColor' => json_encode($categoryColor),
            'categoryCount' => json_encode($categoryCount),

            'dates' => json_encode($dates),
            'bookingsCount' => json_encode($bookingsCount),
            // 'todayOrders' => $todayBookings

            'stats' => $stats,

        ]);

    }      

}
