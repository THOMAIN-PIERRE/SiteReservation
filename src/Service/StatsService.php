<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getStats() {

        $users    = $this->getUserCount();   // Utilisation de la méthode getUsersCount() pour récupérer le nombre total d'utilisateurs
        //dump($users); // Le dump va afficher le nombre d'utilisateurs de type number
        $ads      = $this->getAdsCount();   // Utilisation de la méthode getAdsCount() pour récupérer le nombre total d'utilisateurs
        $bookings = $this->getBookingsCount();
        // $bookingsAmount = $this->getBookingsAmount();
        $comments = $this->getCommentsCount();

        $waitingComment = $this->getWaitingCommentCount();

        $validatedComment = $this->getValidatedCommentCount();

        $waitingAd = $this->getWaitingAdCount();

        $validatedAd = $this->getValidatedAdCount();

        $waitingUser = $this->getWaitingUserCount();

        $validatedUser = $this->getValidatedUserCount();


        // La fonction compact() de php permet de créer un tableau associatif en nomant les clés de la même façon que leurs valeurs
        return compact('users', 'ads', 'bookings', 'comments', 'waitingComment', 'validatedComment', 'waitingAd', 'validatedAd', 'waitingUser', 'validatedUser');
    }



    // getResult() récupère les résultats sous forme d'objets Entité (ici des objets de User.php)

    /**
     * Permet de récupérer le nombre total d'utilisateur dans notre bdd (via une requête DQL, doctrine)
     * UTILE DANS NOTRE DASHBOARD (pour les statistiques)
     * createQuery() permet d'écrire une requête DQL (requête Doctrine), sous forme de chaine de caractères
     * getSingleScalarResult() permet d'obtenir le résultat sous la forme d'une variable scalaire simple
     *
     * @return number
     */
    public function getUserCount() {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount() {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

    public function getBookingsCount() {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getCommentsCount() {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    // Fonction permettant de compter les commentaires en attente de validation
    public function getWaitingCommentCount(){
        return $this->manager->createQuery("SELECT COUNT(c) FROM App\Entity\Comment c WHERE c.status = 'No validated'")->getSingleScalarResult();
    }

    // Fonction permettant de compter les commentaires validés
    public function getValidatedCommentCount(){
        return $this->manager->createQuery("SELECT COUNT(c) FROM App\Entity\Comment c WHERE c.status = 'Validated'")->getSingleScalarResult();
    }

    // Fonction permettant de compter les annonces en attente de validation
    public function getWaitingAdCount(){
        return $this->manager->createQuery("SELECT COUNT(a) FROM App\Entity\Ad a WHERE a.status = 'No validated'")->getSingleScalarResult();
    }

    // Fonction permettant de compter les annonces validées
    public function getValidatedAdCount(){
        return $this->manager->createQuery("SELECT COUNT(a) FROM App\Entity\Ad a WHERE a.status = 'Validated'")->getSingleScalarResult();
    }

    // Fonction permettant de compter les user en attente de validation
    public function getWaitingUserCount(){
        return $this->manager->createQuery("SELECT COUNT(u) FROM App\Entity\User u WHERE u.status = 'No validated'")->getSingleScalarResult();
    }

    // Fonction permettant de compter les user validées
    public function getValidatedUserCount(){
        return $this->manager->createQuery("SELECT COUNT(u) FROM App\Entity\User u WHERE u.status = 'Validated'")->getSingleScalarResult();
    }


    public function getAdsStats($direction) {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $direction
        )->setMaxResults(5)
        ->getResult();
    }



        /**
     * Permet de récupérer les meilleurs annonces (les mieux notées)
     *
     * @return tableau
     */
    public function getBestAds(){
        /*      EXPLICATION DE LA REQUETE DQL QUI SUIT (requête doctrine)
        
            SELECT...: Selection (via la fonction d'agrégation doctrine AVG qui calcul la moyenne d'une note) d'un commentaire "c" (c que l'on va initialiser un peu plus bas) 
                comme (as) note (cad que cette moyenne sera nommé "note"), Selection aussi du titre de l'annonce "a" ("a" que l'on va initialiser un peu plus bas)
                et Selection de l'id de l'annonce, et Selection du firstName de l'utilisateur "u" ("u" que l'on va initialiser un peu plus bas), et Selection du 
                lastName de l'utilisateur ainsi que de l'image de son avatar 
            FROM... : Toute cette sélection sera faite à partir de l entité Comment.php et on initialise c ici donc comme Comment.php
            JOIN c.ad.a: On fait un jointure sur l annonce du commentaire (c est maintenant un commentaire, et possède l attribut ad qui est en relation avec l annonce),
                et on le nomme a (a représente maintenant l entité Ad.php)
            JOIN a.author u: On fait un jointure sur l auteur de l annonce, et on le nomme u (u va ainsi représenter l entité User.php)
            GROUP BY a: Groupé par annonces
            ORDER BY note DESC: Ordonné par note descendante (donc la premiere note sera la plus haute)
        */
        // REQUETE POUR OBTENIR LES MEILLEURS ANNONCES
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture 
            FROM App\Entity\Comment c 
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a 
            ORDER BY note DESC' 
        )
        ->setMaxResults(5) // On précise une limite à notre requête de récupération des notes (limite de 5)
        ->getResult(); // On récup le résultat (sous forme d'une entité)
        // dump($bestAds); // Affiche 5 tableaux avec les notes et info que l'on voulait
    }
    
    /**
     * Permet de récupérer les pires annonces (les moinb bien notées)
     *
     * @return tableau
     */
    public function getWorstAds(){
        // REQUETE POUR OBTENIR LES PIRES ANNONCES
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture 
            FROM App\Entity\Comment c 
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a 
            ORDER BY note ASC' 
        )
        ->setMaxResults(5) // On précise une limite à notre requête de récupération des notes (limite de 5)
        ->getResult(); // On récup le résultat (sous forme d'une entité)
        // dump($worstAds); // Affiche 5 tableaux avec les notes les pires et infos que l'on voulait
    }



    

//---------------------------------------------------------------------------------------------------------------------

    // /**
    //  * Requêtes pour afficher toutes les commandes converties (=payées) passées aujourd'hui (Requête DQL)
    //  *
    //  * @return tableau
    //  */
    // public function todayBookings($direction){
    //     return $this->manager->createQuery(
    //         'SELECT b.createdAt as date, b.price, b.startDate, b.endDate,
    //         FROM App\Entity\Booking b
    //         WHERE b.createdAt >= current_Date()
    //         ORDER BY date ' . $direction
    //     )
    //     ->setMaxResults(5)
    //     ->getResult();
    // }


    // Requêtes pour afficher toutes les commandes converties (=payées) passées aujourd'hui (Requête DQL)
    public function todayBookings($direction){
        return $this->manager->createQuery(
            'SELECT b.createdAt, b.startDate, b.endDate, b.amount, b.id
            FROM App\Entity\booking b
            WHERE b.createdAt >= current_Date()
            ORDER BY b.id ' . $direction
        )
        // ->setMaxResults(5)
        ->getResult();
    }

    // Requêtes pour afficher le montant total des réservations effectuées ce jour (Requête DQL)
    // public function todayBookingsAmount(){
    //     return $this->manager->createQuery(
    //         'SELECT (b.createdAt) as date, SUM(b.amount) as depense
    //         FROM App\Entity\booking b
    //         WHERE b.createdAt >= current_Date()
    //         GROUP BY date
    //         ORDER BY date DESC'
    //     )
    //     ->getResult();
    // }

    // public function getUsersStats2($direction){
    //     return $this->manager->createQuery(
    //         'SELECT (o.myOrder) as commande, (SUM(o.total)/100) as depense, u.firstname, u.lastname, u.createdAt
    //         FROM App\Entity\OrderDetails o
    //         JOIN o.myOrder r
    //         JOIN r.user u
    //         GROUP BY commande
    //         ORDER BY commande ' . $direction
    //     )
        
    //     ->getResult();


    // public function getBookingsAmount() {
    //     return $this->manager->createQuery('SELECT SUM(b.createdAt) as date, b.amount as montant  FROM App\Entity\booking b WHERE b.createdAt >= current_Date() GROUP BY montant ORDER BY montant')->getResult();
    // }
}


