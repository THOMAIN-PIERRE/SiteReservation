<?php

namespace App\Repository;


use App\Entity\Ad;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;



/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    /**
    * @var PaginatorInterface
    */
    private $paginator;


    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Ad::class);
        $this->paginator = $paginator;
    }
   
    public function findBestAds($limit) {
        return $this->createQueryBuilder('a')
                    ->select('a as annonce, AVG(c.rating) as avgRatings')
                    ->join('a.comments', 'c')
                    ->groupBy('a')
                    ->orderBy('avgRatings', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }


    // public function findAdValidated() {
    //     return $this->createQueryBuilder('a')
    //                 ->select('a as annonce, a.status as statut')
    //                 ->where('a.status = Validated')
    //                 // ->join('a.comments', 'c')
    //                 ->groupBy('annonce')
    //                 ->orderBy('createdAt', 'DESC')
    //                 // ->setMaxResults($limit)
    //                 ->getQuery()
    //                 ->getResult();
    // }



    // // Méthode pour mise en place d'un champs de recherche par titre d'annonce
    // public function findByStatus($status)
    // {
    //     return $this->createQueryBuilder('ad')
    //     ->where('ad.title like :title')
    //     ->setParameter('title', $status)
    //     ->getQuery()
    //     ->getResult();
    // }

    
    // // Méthode pour mise en place d'un champs de recherche par titre d'annonce
    // public function findByString($string)
    // {
    //     return $this->createQueryBuilder('ad')
    //     ->where('ad.title like :title')
    //     ->setParameter('title', $string)
    //     ->getQuery()
    //     ->getResult();
    // }


    /**
    * Pour récupèrer les articles en lien avec une recherche par catégorie ($search contient les catégories que nous avons cochées dans la liste de la barre latérale)
    * @return Ad[] Returns an array of Article objects
    * @return PaginationInterface
    */
    // public function searchData($criteria)
    public function findSearch(SearchData $search): PaginationInterface
    {
        
            $query = $this
        
                ->createQueryBuilder('ad')
                ->select('category', 'ad')
                ->join('ad.category', 'category');
        


            // if(!empty($search->categories)) {

            //     $query = $query
                       
            //     ->andWhere('category IN (:categories)')
            //     ->orderBy('ad.createdAt', 'DESC')
            //     ->setParameter('categories', $search->categories);
       
            // }


            

            // if(!empty($search->q)) {
            //             $query = $query
            //                 ->andWhere('produit.designation LIKE :q')
            //                 ->orderBy('produit.createdAt', 'DESC')
            //                 ->setParameter('q', "%{$search->q}%");
            //                 // dump($query);
            //                 // die();        
            // }



            // if(!empty($search->status)) {
            //     $query = $query
            //         ->andWhere('ad.status LIKE :status') // ":city = ma ville = la ville que j'indique en paramètre de recherche
            //         ->orderBy('ad.createdAt', 'DESC')
            //         ->setParameter('status', "%{$search->status}%");
            //         // dump($query);
            //         // die();        
            // }



            if(!empty($search->city)) {
                $query = $query
                    ->andWhere('ad.city LIKE :city') // ":city = ma ville = la ville que j'indique en paramètre de recherche
                    ->orderBy('ad.createdAt', 'DESC')
                    ->setParameter('city', "%{$search->city}%");
                    // dump($query);
                    // die();        
            }


            // if(!empty($search->rate)) {
            //     $query = $query
            //         ->andWhere('ad.rate LIKE :rate')
            //         ->orderBy('ad.createdAt', 'DESC')
            //         ->setParameter('rate', "%{$search->rate}%");
            //         // dump($query);
            //         // die();        
            // }


            if(!empty($search->categories)) {

                $query = $query
                       
                ->andWhere('category IN (:categories)')
                ->orderBy('ad.createdAt', 'DESC')
                ->setParameter('categories', $search->categories);
       
            }



            // if (!empty($search->city)){
            //     $query = $query
            //         ->andWhere('ad.city LIKE :city')
            //         ->setParameter('city', "%{$search->city}%");
            // }


    
            if (!empty($search->min)){
                $query = $query
                    ->andWhere('ad.price >= :min')
                    ->setParameter('min', $search->min);
            }


    
            if (!empty($search->max)){
                $query = $query
                    ->andWhere('ad.price <= :max')
                    ->setParameter('max', $search->max);
            }



            // if (!empty($search->rateMin)){
            //     $query = $query
            //         ->andWhere('getAvgRatings >= :rateMin')
            //         ->setParameter('ratMin', $search->rateMin);
            // }



            // if (!empty($search->rateMax)){
            //     $query = $query
            //         ->andWhere('ad.avgRatings <= :rateMax')
            //         ->setParameter('rateMax', $search->rateMax);
            // }



                        
           
            // $query = $query->orderBy('ad.createdAt', 'DESC')->getQuery();
            $query = $query->getQuery();

            // return $query->getResult();

            $query = $query->getResult();
            
            return $this->paginator->paginate(
                $query,
                $search->page,
                // Nombre d'annonces affichées par page sur le site
                6
            );
    }


    // // Méthode pour mise en place d'un champs de recherche par titre d'annonce
    // public function findByStatus($status)
    // {
    //     return $this->createQueryBuilder('ad')
    //     ->where('ad.title like :title')
    //     ->setParameter('title', $status)
    //     ->getQuery()
    //     ->getResult();


    //     if (!empty($search->status)){
    //         $query = $query
    //             ->andWhere('ad.status = :status')
    //             ->setParameter('min', $search->min);
    //     }
    // }



    
}
