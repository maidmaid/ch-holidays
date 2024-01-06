<?php

namespace App\Entity;

use App\Repository\HolidayTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HolidayTypeRepository::class)]
class HolidayType
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 10)]
    private ?string $id = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Holiday::class)]
    private Collection $holidays;

    public function __construct()
    {
        $this->holidays = new ArrayCollection();
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Holiday>
     */
    public function getHolidays(): Collection
    {
        return $this->holidays;
    }

    public function addHoliday(Holiday $holiday): static
    {
        if (!$this->holidays->contains($holiday)) {
            $this->holidays->add($holiday);
            $holiday->setType($this);
        }

        return $this;
    }

    public function removeHoliday(Holiday $holiday): static
    {
        if ($this->holidays->removeElement($holiday)) {
            // set the owning side to null (unless already changed)
            if ($holiday->getType() === $this) {
                $holiday->setType(null);
            }
        }

        return $this;
    }
}
