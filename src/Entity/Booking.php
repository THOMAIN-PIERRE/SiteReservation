<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks() // ATTENTION, a différents évènements du cycle de vie de cette entité, nous avons relié des fonctions !
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit être ultérieur à la date d'aujourd'hui", groups={"front"})
     * 
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de départ doit être plus éloignée que la date d'arrivée")
     * 
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reviser;


    /**
     * Callback appelé à chaque fois qu'on créé une réservation 
     * (gestion de la date de réservation et du montant de la réservation pour les ajouter automatiquement en BDD)
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function prePersist()
    {
        if(empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if(empty($this->amount)){
            // Prix de l'annonce * nombre de jour
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function isBookableDates()
    {
        // 1. Il faut connaitre les dates qui sont impossibles pour l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        // 2. Il faut comparer les dates choisies avec les dates impossibles
        $bookingDays = $this->getDays();

        $formatDay = function($day){
            return $day->format('Y-m-d');
        };

        // Tableau des chaines de caractères de mes journées
        $days           = array_map($formatDay, $bookingDays);
        $notAvailable   = array_map($formatDay, $notAvailableDays);

        foreach ($days as $day) {
            if(array_search($day, $notAvailable) !== false) return false;
        }

        return true;
    }
    /**
     * Permet de récupérer un tableau des journées qui correspondent à ma réservation
     *
     * @return array Un tableau d'objets DateTime représentant les jours de la réservation
     */
    public function getDays()
    {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        );

        $days = array_map(function($dayTimestamp){
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }

    public function getDuration() 
    {
        // Pour calculer le nombre de jours qui existent entre 2 dates ! L'objet récupéré ($diff) est de type interval (= intervale entre 2 dates)
        $diff = $this->endDate->diff($this->startDate);
        // Retourne moi le nombre de jours qui se sont écoulés entre la start et la endDate.
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getReviser(): ?int
    {
        return $this->reviser;
    }

    public function setReviser(?int $reviser): self
    {
        $this->reviser = $reviser;

        return $this;
    }
}
