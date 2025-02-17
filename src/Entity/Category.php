<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, SoftSkill>
     */
    #[ORM\OneToMany(targetEntity: SoftSkill::class, mappedBy: 'category')]
    private Collection $softSkills;

    public function __construct()
    {
        $this->softSkills = new ArrayCollection();
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
}
