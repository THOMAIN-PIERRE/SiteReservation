<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\StatsService;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination, StatsService $statsService)
    {

        $pagination->setEntityClass(Comment::class)
                   ->setLimit(5)
                   ->setPage($page);

        $stats = $statsService->getStats();

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats
        ]);
    }



    /**
     * Permet d'avoir accès à la liste des commentaires à valider dans l'administration
     *  
     * @Route("/admin/comment/{page<\d+>?1}/toValidate", name="admin_comment_toValidate")
     */
    public function indexToValidate(CommentRepository $repo, $page, PaginationService $pagination, StatsService $statsService){

        $pagination->setEntityClass(Comment::class)
                   ->setPage($page);

        $stats = $statsService->getStats();
                   
        return $this->render('admin/comment/indexToValidate.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats
            
        ]);
    }




    /**
     * Permet de modifier un commentaire
     * 
     * @Route("admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager, StatsService $statsService) 
    {
        $user = $this->getUser()->getId();

        $stats = $statsService->getStats();

        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $comment->setReviser($user);
            $comment->setUpdatedAt(new \DateTime());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire n° {$comment->getId()} a été modifié !"
            );

            return $this->redirectToRoute('admin_comment_index');
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'stats' => $stats
        ]);
    }

    
    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param EntityManagerInterface $manager

     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();
    
        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getAuthor()->getFullName()}</strong> a bien été supprimé"
        ); 

        return $this->redirectToRoute('admin_comment_index');
    }
    
}

