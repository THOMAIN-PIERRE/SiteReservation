<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\AdminUserType;
use App\Service\StatsService;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    // /**
    //  * @Route("/admin/user", name="admin_user")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('admin_user/index.html.twig', [
    //         'controller_name' => 'AdminUserController',
    //     ]);
    // }


     /**
     *Permet d'avoir accès à la liste des profils d'activité des utilisateurs dans l'administration
     *  
     * @Route("/admin/user/activity/{page<\d+>?1}", name="admin_user_index")
     */
    public function index(UserRepository $repo, $page, PaginationService $pagination, StatsService $statsService){

        $repo = $this->getDoctrine()->getRepository(User::class);

        $user = $repo->findAll();

        $pagination->setEntityClass(User::class)
                   ->setPage($page);

        $stats = $statsService->getStats();
                   
        return $this->render('admin/user/activity/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
            'stats' => $stats
            
        ]);
    }


     /**
     *Permet d'avoir accès à la liste des profils détaillés des utilisateurs dans l'administration
     *  
     * @Route("/admin/user/details/{page<\d+>?1}", name="admin_user_details")
     */
    public function details(UserRepository $repo, $page, PaginationService $pagination, StatsService $statsService){

        $repo = $this->getDoctrine()->getRepository(User::class);

        $user = $repo->findAll();

        $pagination->setEntityClass(User::class)
                   ->setPage($page);

        $stats = $statsService->getStats();
                   
        return $this->render('admin/user/details/details.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
            'stats' => $stats
            
        ]);
    }


    /**
     *Permet d'avoir accès à la liste des profils détaillés à valider des utilisateurs dans l'administration
     *  
     * @Route("/admin/user/details/toValidate/{page<\d+>?1}", name="admin_user_details_toValidate")
     */
    public function detailsToValidate(UserRepository $repo, $page, PaginationService $pagination, StatsService $statsService){

        $repo = $this->getDoctrine()->getRepository(User::class);

        $user = $repo->findAll();

        $pagination->setEntityClass(User::class)
                   ->setPage($page);

        $stats = $statsService->getStats();
                   
        return $this->render('admin/user/details/detailsToValidate.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
            'stats' => $stats
            
        ]);
    }


     /**
     *Permet d'afficher le formulaire de création d'utilisateurs dans l'administration
     * 
     * @Route("/admin/user/new", name="admin_user_new")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, StatsService $statsService)
        {
            $stats = $statsService->getStats();

            $user = $this->getUser();

            $user = new User();
            // $adminRole = new Role();

            // $adminRole ->setIntitule('ROLE_ADMIN');
            // $role = $this->getParameter('security.role_hierarchy.role');

            $form = $this->createForm(AdminUserType::class, $user);

            $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            //$users->setCreatedAt(new \DateTime());
            // $users->setUtilisateurs($user);

                $manager->persist($user);
                // $manager->persist($adminRole);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'utilisateur <strong>{$user->getUsername()} </strong> a été créé !"
                    );
        
                    return $this->redirectToRoute("admin_user");
            }
                        
            return $this->render('admin/user/new.html.twig', [
                'form' => $form->createView(),
                'stats' => $stats,
                // 'adminRole' => $adminRole
            ]);
        }

    /**
     *Permet d'éditer et de modifier les profils détaillés des utilisateurs dans l'administration 
     * 
     *  @Route("/admin/user/details/{id}/edit", name="admin_user_edit")
     * @param User $user
     * @return Response
     */
    public function edit($id, User $user, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, StatsService $statsService)
    {
        $stats = $statsService->getStats();

        $form = $this->createForm(AdminUserType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
            'success',
            "Les informations de l'utilisateur <strong>{$user->getUsername()} </strong> ont été modifiées !"
            );

            return $this->redirectToRoute("admin_user_details");
        }

        return $this->render('admin/user/edit.html.twig', [
                'form' => $form->createView(),
                'stats' => $stats,
                'user' => $user,
            ]);
    }



     /**
     *Permet d'afficher le formulaire de modération dans l'admin (OK)
     * 
     *  @Route("/admin/user/details/moderate/{id}", name="admin_user_moderate")
     * @param User $user
     * @return Response
     */
    public function moderate($id, User $user, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, StatsService $statsService)
    {
        $stats = $statsService->getStats();

        // $user = $this->getUser();

        // $user = new User();

        $form = $this->createForm(AdminUserType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
            'success',
            "Vous avez statué sur la demande d'inscription de <strong>{$user->getFullname()} !"
            );

            return $this->redirectToRoute("admin_user_details");
        }

        return $this->render('admin/user/moderate.html.twig', [
                'form' => $form->createView(),
                'stats' => $stats,
                'user' => $user
            ]);
    }



    /**
     * Permet de supprimer un utilisateur
     * 
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     *
     * @param User $user
     * @param EntityManagerInterface $manager

     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {
        $manager->remove($user);
        $manager->flush();
    
        $this->addFlash(
            'success',
            "Vous avez refusé la demande d'inscription de <strong>{$user->getFullName()}</strong>. N'oubliez pas de supprimer cette demande de la liste des demandes en atente de validation"
        ); 

        return $this->redirectToRoute('admin_user_details');
    }
 
}
