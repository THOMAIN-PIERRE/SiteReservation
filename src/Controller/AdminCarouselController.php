<?php

namespace App\Controller;

use App\Entity\Carousel;
use App\Service\StatsService;
use App\Form\AdminCarouselType;
use App\Service\PaginationService;
use App\Repository\CarouselRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCarouselController extends AbstractController
{
    // /**
    //  * @Route("/admin/carousel", name="admin_carousel_index")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('admin/carousel/index.html.twig', [
    //         'controller_name' => 'AdminCarouselController',
    //     ]);
    // }


     /**
     *Permet d'avoir accès à la liste des contenus du carousel dans l'administration
     *  
     * @Route("/admin/carousel/{page<\d+>?1}", name="admin_carousel_index")
     */
    public function index(CarouselRepository $repo, $page, PaginationService $pagination, StatsService $statsService){

        $pagination->setEntityClass(Carousel::class)
                   ->setPage($page);

        $stats = $statsService->getStats();
                   
        return $this->render('admin/carousel/index.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats,
            
        ]);
    }

    /**
     *Permet d'afficher le formulaire de création de nouveau contenu pour le carousel dans l'administration
     * 
     * @Route("/admin/carousel/new", name="admin_carousel_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, StatsService $statsService)
        {
            $stats = $statsService->getStats();

            $user = $this->getUser();

            $carousel = new Carousel();

            $form = $this->createForm(AdminCarouselType::class, $carousel);

            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid()){

                $manager->persist($carousel);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le nouvel élément <strong>(n° {$carousel->getId()})</strong> a été créé dans le carousel !"
                    );
        
                    return $this->redirectToRoute("admin_carousel_index");
            }
                        
            return $this->render('admin/carousel/new.html.twig', [
                'carousel' => $carousel,
                'form' => $form->createView(),
                'stats' => $stats,
            ]);
        }
    
     /**
     *Permet d'éditer des contenus du carousel dans l'administration
     * 
     * @Route("/admin/carousel/{id}/edit", name="admin_carousel_edit")
     * @param Carousel $carousel
     * @return Response
     */
    public function edit(Carousel $carousel, Request $request, EntityManagerInterface $manager, StatsService $statsService)
    {
        $stats = $statsService->getStats();

        $form = $this->createForm(AdminCarouselType::class, $carousel);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $carousel->setCreatedAt(new \DateTime());
            // $carousel->setUtilisateurs($user);

            $manager->persist($carousel);
            $manager->flush();

            // return $this->redirectToRoute("admin_carousel");

            $this->addFlash(
            'success',
            "L'élément n° <strong>{$carousel->getId()}</strong> du carousel a été modifiée !"
            );

            return $this->redirectToRoute("admin_carousel_index");
        }

        return $this->render('admin/carousel/edit.html.twig', [
                'carousel' => $carousel,
                'form' => $form->createView(),
                'stats' => $stats,
            ]);
    }
        

    /**
     *Permet de supprimer des articles dans l'administration
     * 
     * @Route("/admin/carousel/{id}/delete", name="admin_carousel_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Carousel $carousel, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($carousel);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'élément <strong>(n° {$carousel->getId()})</strong> a été supprimé !"
            );

        return $this->redirectToRoute('admin_carousel_index');
    }
}
