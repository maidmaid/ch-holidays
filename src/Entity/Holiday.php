<?php

namespace App\Entity;

use App\Repository\HolidayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HolidayRepository::class)]
class Holiday
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Canton::class, inversedBy: "holidays")]
    #[ORM\JoinColumn(nullable: false)]
    private $canton;

    #[ORM\Column(type: "date_immutable")]
    private $date;

    #[ORM\ManyToOne(inversedBy: 'holidays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HolidayType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanton(): ?Canton
    {
        return $this->canton;
    }

    public function setCanton(?Canton $canton): self
    {
        $this->canton = $canton;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?HolidayType
    {
        return $this->type;
    }

    public function setType(?HolidayType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
