<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sigle = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoPath = null;

    /**
     * @var Collection<int, Iut>
     */
    #[ORM\ManyToMany(targetEntity: Iut::class, mappedBy: 'formation')]
    private Collection $iuts;

    public function __construct()
    {
        $this->iuts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): static
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(string $logoPath): static
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    /**
     * @return Collection<int, Iut>
     */
    public function getIuts(): Collection
    {
        return $this->iuts;
    }

    public function addIut(Iut $iut): static
    {
        if (!$this->iuts->contains($iut)) {
            $this->iuts->add($iut);
            $iut->addFormation($this);
        }

        return $this;
    }

    public function removeIut(Iut $iut): static
    {
        if ($this->iuts->removeElement($iut)) {
            $iut->removeFormation($this);
        }

        return $this;
    }
}
