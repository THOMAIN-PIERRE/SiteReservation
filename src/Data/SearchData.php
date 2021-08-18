<?php

namespace App\Data;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchData
{
    // /**
    // * Système permettant de rentrer un mot clé. C'est une chaîne de caractères
    // * @Route("/main", name="main")
    // *
    // */
    // public function searchArticle(Request $request)
    // {
    //     $form = $this->createFormBuilder(SearchType::class);

    //     return $this->render('main/index.html.twig', [

    //     // J'envoie le formulaire à ma vue
    //     'form' => $form->createView(),

    //     ]);
    // }



    // Cet objet nous permet de représenter nos données. Nous pourrons utiliser cet objet au niveau de notre formulaire


     /**
     * On défini le n° de page par défaut (pour pagination)
     * @var int
     */
    public $page = 1;


    /**
     * Système permettant de rentrer un mot clé. C'est une chaîne de caractères
     * @var string
     */
    public $city = '';


    /**
    *@var category[]
    */
    public $categories = [];


    /**
     * @var null|integer
     */
    public $min;


    /**
     * @var null|integer
     */
    public $max;


    // /**
    //  * Système permettant de rentrer un mot clé. C'est une chaîne de caractères
    //  * @var null|integer
    //  */
    // public $rate;


    // /**
    //  * @var null|integer
    //  */
    // public $rateMin;


    // /**
    //  * @var null|integer
    //  */
    // public $rateMax;


}