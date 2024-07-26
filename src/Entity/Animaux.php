<?php

namespace App\Entity;

use App\Repository\AnimauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimauxRepository::class)]
class Animaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\ManyToOne(inversedBy: 'animauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Espece $Espece = null;

    #[ORM\ManyToOne(inversedBy: 'animauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habitat $Habitat = null;

    /**
     * @var Collection<int, Nourriture>
     */
    #[ORM\OneToMany(targetEntity: Nourriture::class, mappedBy: 'animal')]
    private Collection $nourritures;

    /**
     * @var Collection<int, Veterinaire>
     */
    #[ORM\OneToMany(targetEntity: Veterinaire::class, mappedBy: 'Animal')]
    private Collection $veterinaires;



    public function __construct()
    {
        $this->nourritures = new ArrayCollection();
        $this->veterinaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getEspece(): ?Espece
    {
        return $this->Espece;
    }

    public function setEspece(?Espece $Espece): static
    {
        $this->Espece = $Espece;

        return $this;
    }

    public function getHabitat(): ?Habitat
    {
        return $this->Habitat;
    }

    public function setHabitat(?Habitat $Habitat): static
    {
        $this->Habitat = $Habitat;

        return $this;
    }

    /**
     * @return Collection<int, Nourriture>
     */
    public function getNourritures(): Collection
    {
        return $this->nourritures;
    }

    public function addNourriture(Nourriture $nourriture): static
    {
        if (!$this->nourritures->contains($nourriture)) {
            $this->nourritures->add($nourriture);
            $nourriture->setAnimal($this);
        }

        return $this;
    }

    public function removeNourriture(Nourriture $nourriture): static
    {
        if ($this->nourritures->removeElement($nourriture)) {
            // set the owning side to null (unless already changed)
            if ($nourriture->getAnimal() === $this) {
                $nourriture->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Veterinaire>
     */
    public function getVeterinaires(): Collection
    {
        return $this->veterinaires;
    }

    public function addVeterinaire(Veterinaire $veterinaire): static
    {
        if (!$this->veterinaires->contains($veterinaire)) {
            $this->veterinaires->add($veterinaire);
            $veterinaire->setAnimal($this);
        }

        return $this;
    }

    public function removeVeterinaire(Veterinaire $veterinaire): static
    {
        if ($this->veterinaires->removeElement($veterinaire)) {
            // set the owning side to null (unless already changed)
            if ($veterinaire->getAnimal() === $this) {
                $veterinaire->setAnimal(null);
            }
        }

        return $this;
    }
}
