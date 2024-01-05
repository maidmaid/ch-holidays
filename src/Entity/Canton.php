<?php

namespace App\Entity;

use App\Repository\CantonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CantonRepository::class)
 * @ORM\Table(name="canton", indexes={@ORM\Index(name="canton_idx", columns={"canton"})})
 */
class Canton
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=3)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $canton;

    /**
     * @ORM\Column(type="text", length=2)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $language;

    /**
     * @ORM\OneToMany(targetEntity=Holiday::class, mappedBy="canton")
     */
    private $holidays;

    public function __construct()
    {
        $this->holidays = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCanton(): ?string
    {
        return $this->canton;
    }

    public function setCanton(string $canton): self
    {
        $this->canton = $canton;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection<int, Holiday>
     */
    public function getHolidays(): Collection
    {
        return $this->holidays;
    }

    public function addHoliday(Holiday $holiday): self
    {
        if (!$this->holidays->contains($holiday)) {
            $this->holidays[] = $holiday;
            $holiday->setCanton($this);
        }

        return $this;
    }

    public function removeHoliday(Holiday $holiday): self
    {
        if ($this->holidays->removeElement($holiday)) {
            // set the owning side to null (unless already changed)
            if ($holiday->getCanton() === $this) {
                $holiday->setCanton(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->canton, $this->text);
    }
}
