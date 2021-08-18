<?php

namespace App\Controller;


use App\Entity\Ad;
use App\Form\AdType;
use App\Form\AdminAdType;
use App\Service\StatsService;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * Pour afficher les annonces dans l'admin
     * 
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination, StatsService $statsService)
    {
        $pagination->setEntityClass(Ad::class)
                   ->setPage($page);

        $stats = $statsService->getStats();

        return $this->render('admin/ad/index.html.twig', [
            // On passe à notre template une variable pagination qui représente mon objet pagination
            'pagination' => $pagination,
            'stats' => $stats,
            'ads' => $repo->findAll()
        ]);
    }


    /**
     * Permet d'avoir accès à la liste des annonces à valider dans l'administration
     * 
     * @Route("/admin/ads/{page<\d+>?1}/toValidate", name="admin_ads_toValidate")
     */
    public function indexToValidate(AdRepository $repo, $page, PaginationService $pagination, StatsService $statsService)
    {
        $pagination->setEntityClass(Ad::class)
                   ->setPage($page);

        $stats = $statsService->getStats();

        return $this->render('admin/ad/indexToValidate.html.twig', [
            // On passe à notre template une variable pagination qui représente mon objet pagination
            'pagination' => $pagination,
            'stats' => $stats,
            'ads' => $repo->findAll()
        ]);
    }


    /**
     * Permet de créer une nouvelle annonce dans l'admin
     *
     * @Route("/admin/ads/new", name="admin_ads_new")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, StatsService $statsService)
    {

        $stats = $statsService->getStats();

        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        // dd($form);

        
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());
            $ad->setCreatedAt(new \DateTime());

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
            
            return $this->redirectToRoute("admin_ads_index", [
                'slug' => $ad->getSlug(),
                'stats' => $stats,
                
            ]);
        }

        return $this->render('admin/ad/new.html.twig', [
            'stats' => $stats,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer une annonce dans l'admin
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Ad $ad, EntityManagerInterface $manager) {


        if(count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations"
            );
        }else{

            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'succes',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée"
            ); 
        }


        return $this->redirectToRoute('admin_ads_index');
    }


    /**
     * Permet d'afficher le formulaire d'edition et de modification d'une annnonce dans l'admin
     * 
     * @Route("admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager, StatsService $statsService) 
    {
        $user = $this->getUser()->getId();

        $stats = $statsService->getStats();

        $form = $this->createForm(AdminAdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $ad->setReviser($user);
            $ad->setUpdatedAt(new \DateTime());

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce n° {$ad->getId()} a été modifiée avec succès."
            );

            return $this->redirectToRoute('admin_ads_index');
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'stats' => $stats,
            'form' => $form->createView()
        ]);
    }
}
