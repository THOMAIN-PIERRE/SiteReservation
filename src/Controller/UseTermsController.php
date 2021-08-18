<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UseTermsController extends AbstractController
{
    /**
     * @Route("/use/terms/cgu", name="use_terms_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('use_terms/cgu.html.twig');
    }


    /**
     * @Route("/use/terms/politiqueConfidentialite", name="use_terms_politiqueConfidentialite")
     */
    public function politiqueConfidentialite(): Response
    {
        return $this->render('use_terms/politiqueConfidentialite.html.twig');
    }
}
