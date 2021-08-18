<?php

namespace App\Controller;

use App\Entity\Carousel;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    

    /**
     * @Route("/", name="welcome")
     */
    public function welcome()
    {
        return $this->render('welcome.html.twig');
    }

    /**
     * @Route("/home", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo){ // On injecte les repository des annonnces et utilisateurs pour utiliser les méthodes findBestAds() et findBestUsers()

        $carousels = $this->entityManager->getRepository(Carousel::class)->findAll();
        
        return $this->render('home.html.twig',[
                'carousels' => $carousels,
                // bestads représente donc un tableau de 3 tableaux des meilleurs annonce et leur note moyenne
                'bestAds' => $adRepo->findBestAds(3), // Le paramètre ici permet de modifier le nombre d'annonces stars de la page Home
                'bestUsers' => $userRepo->findBestUsers(3)
            ]
        );

    }


}

?>