<?php

namespace App\Entity;

use App\Repository\SoftSkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoftSkillRepository::class)]
class SoftSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'softSkills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * @var Collection<int, Cv>
     */
    #[ORM\ManyToMany(targetEntity: Cv::class, mappedBy: 'softSkills')]
    private Collection $cvs;

    public function __construct()
    {
        $this->cvs = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Cv>
     */
    public function getCvs(): Collection
    {
        return $this->cvs;
    }

    public function addCv(Cv $cv): static
    {
        if (!$this->cvs->contains($cv)) {
            $this->cvs->add($cv);
            $cv->addSoftskill($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): static
    {
        if ($this->cvs->removeElement($cv)) {
            $cv->removeSoftskill($this);
        }

        return $this;
    }
}
