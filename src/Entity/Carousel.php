<?php

namespace App\Entity;

use App\Repository\CarouselRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarouselRepository::class)
 */
class Carousel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btn_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btn_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $illustration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getBtnTitle(): ?string
    {
        return $this->btn_title;
    }

    public function setBtnTitle(?string $btn_title): self
    {
        $this->btn_title = $btn_title;

        return $this;
    }

    public function getBtnUrl(): ?string
    {
        return $this->btn_url;
    }

    public function setBtnUrl(?string $btn_url): self
    {
        $this->btn_url = $btn_url;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }
}
