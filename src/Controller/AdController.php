<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Data\SearchData;
use App\Form\SearchType;
use App\Form\SearchType2;
use App\Service\StatsService;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{

    /**
     * Permet d'afficher toutes nos annonces à l'écran
     * 
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo, Request $request)
    {
        // $ads = $repo->findByStatus(['status' => 'Validated']);
        // $ads = $repo->findAdValidated();
        // $ad = $repo->findBy(['status' => "Validated"]);


        $search = new SearchData();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        
        $ads = $repo->findSearch($search);

        // dd($search);
        // dd($ads);
        
        return $this->render('ad/index.html.twig', [
            // 'adValidated' => $adValidated,
            // 'ad' => $ad,
            'ads' => $ads,
            // J'envoie mon form a ma vue
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * Permet de créer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
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
            $ad->setStatus("No validated");

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
            
            return $this->redirectToRoute("ads_show", [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous n'êtes pas l'auteur de cette annonce, vous ne pouvez pas la modifier.")
     *
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser()->getId();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);

                // dd($image);

                $manager->persist($image);
            }

            $ad->setReviser($user);
            $ad->setUpdatedAt(new \DateTime());

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );
            
            return $this->redirectToRoute("ads_show", [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }
    
    /**
     * Permet d'afficher une seule annonce
     * 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show($slug, Ad $ad)
    {

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource.")
     * 
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("ads_index");
    }

}

