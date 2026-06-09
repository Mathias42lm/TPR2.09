<?php

namespace App\Entity;

use App\Repository\UniversiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniversiteRepository::class)]
class Universite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Iut>
     */
    #[ORM\OneToMany(targetEntity: Iut::class, mappedBy: 'universite')]
    private Collection $iuts;

    public function __construct()
    {
        $this->iuts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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
            $iut->setUniversite($this);
        }

        return $this;
    }

    public function removeIut(Iut $iut): static
    {
        if ($this->iuts->removeElement($iut)) {
            // set the owning side to null (unless already changed)
            if ($iut->getUniversite() === $this) {
                $iut->setUniversite(null);
            }
        }

        return $this;
    }
}
