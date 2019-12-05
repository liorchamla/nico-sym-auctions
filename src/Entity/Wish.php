<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WishRepository")
 */
class Wish
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $item;

    /**
     * @ORM\Column(type="integer")
     */
    private $wantedPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?int
    {
        return $this->item;
    }

    public function setItem(int $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getWantedPrice(): ?int
    {
        return $this->wantedPrice;
    }

    public function setWantedPrice(int $wantedPrice): self
    {
        $this->wantedPrice = $wantedPrice;

        return $this;
    }
}
