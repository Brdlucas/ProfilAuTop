<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\SoftSkill;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\Choice(
        choices: [
            "Compétences relationnelles",
            "Gestion et Organisation",
            "Intelligence émotionnelle",
            "Pensée critique et créativité",
            "Apprentissage et adaptabilité",
            "Ethique et professionnalisme"
        ],
    )]
    private ?string $name = null;

    /**
     * @var Collection<int, SoftSkill>
     */
    #[ORM\OneToMany(targetEntity: SoftSkill::class, mappedBy: 'category')]
    private Collection $softSkills;

    /**
     * @var Collection<int, Poi>
     */
    #[ORM\OneToMany(targetEntity: Poi::class, mappedBy: 'category')]
    private Collection $pois;

    public function __construct()
    {
        $this->softSkills = new ArrayCollection();
        $this->pois = new ArrayCollection();
    }

    public function __tostring()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, SoftSkill>
     */
    public function getSoftSkills(): Collection
    {
        return $this->softSkills;
    }

    public function addSoftSkill(SoftSkill $softSkill): static
    {
        if (!$this->softSkills->contains($softSkill)) {
            $this->softSkills->add($softSkill);
            $softSkill->setCategory($this);
        }

        return $this;
    }

    public function removeSoftSkill(SoftSkill $softSkill): static
    {
        if ($this->softSkills->removeElement($softSkill)) {
            // set the owning side to null (unless already changed)
            if ($softSkill->getCategory() === $this) {
                $softSkill->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Poi>
     */
    public function getPois(): Collection
    {
        return $this->pois;
    }

    public function addPoi(Poi $poi): static
    {
        if (!$this->pois->contains($poi)) {
            $this->pois->add($poi);
            $poi->setCategory($this);
        }

        return $this;
    }

    public function removePoi(Poi $poi): static
    {
        if ($this->pois->removeElement($poi)) {
            // set the owning side to null (unless already changed)
            if ($poi->getCategory() === $this) {
                $poi->setCategory(null);
            }
        }

        return $this;
    }
}
