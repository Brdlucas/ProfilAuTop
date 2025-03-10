<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ref = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $born = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postal_code = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $languages = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $licences = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portfolio_url = null;

    #[ORM\Column]
    private bool $is_gpdr = false;

    #[ORM\Column]
    private bool $is_terms = false;

    #[ORM\Column]
    private bool $is_major = false;

    #[ORM\Column(length: 255, nullable: true)]
    private string $image = 'default.png';

    /**
     * @var Collection<int, LoginHistory>
     */
    #[ORM\OneToMany(targetEntity: LoginHistory::class, mappedBy: 'jobless')]
    private Collection $loginHistories;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'applicant')]
    private Collection $offers;

    /**
     * @var Collection<int, Cv>
     */
    #[ORM\OneToMany(targetEntity: Cv::class, mappedBy: 'creator')]
    private Collection $cvs;

    /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: 'employee', orphanRemoval: true)]
    private Collection $experiences;

    /**
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $formations;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?Subscription $subscription = null;

    #[ORM\Column]
    private int $strike = 0;

    #[ORM\Column]
    private bool $is_updated = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_name_at = null;

    /**
     * @var Collection<int, Poi>
     */
    #[ORM\ManyToMany(targetEntity: Poi::class, inversedBy: 'users')]
    private Collection $pois;

    #[ORM\Column]
    private int $cv_count = 0;

    public function __construct()
    {
        $this->loginHistories = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->cvs = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->ref = uniqid($this->firstname . '-' .$this->lastname);
        $this->pois = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updated_at = new \DateTimeImmutable;
    }

    public function isComplete(): bool
    {
        if (!empty($this->firstname) && !empty($this->lastname) && !empty($this->born) && !empty($this->phone) && !empty($this->city) && !empty($this->postal_code)) {
            return true;
        }

        return false;
    }
    public function isComplete2(): bool
    {
        if (!empty($this->licences) && !empty($this->languages)) {
            return true;
        }

        return false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBorn(): ?\DateTimeInterface
    {
        return $this->born;
    }

    public function setBorn(?string $born): self
    {
        $this->born = $born ? new \DateTime($born) : null;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    public function setLanguages(?array $languages): static
    {
        $this->languages = $languages;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getLicences(): ?array
    {
        return $this->licences;
    }

    public function setLicences(?array $licences): static
    {
        $this->licences = $licences;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getPortfolioUrl(): ?string
    {
        return $this->portfolio_url;
    }

    public function setPortfolioUrl(?string $portfolio_url): static
    {
        $this->portfolio_url = $portfolio_url;

        return $this;
    }

    public function isGpdr(): ?bool
    {
        return $this->is_gpdr;
    }

    public function setIsGpdr(bool $is_gpdr): static
    {
        $this->is_gpdr = $is_gpdr;

        return $this;
    }

    public function isTerms(): ?bool
    {
        return $this->is_terms;
    }

    public function setIsTerms(bool $is_terms): static
    {
        $this->is_terms = $is_terms;

        return $this;
    }

    public function isMajor(): ?bool
    {
        return $this->is_major;
    }

    public function setIsMajor(bool $is_major): static
    {
        $this->is_major = $is_major;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, LoginHistory>
     */
    public function getLoginHistories(): Collection
    {
        return $this->loginHistories;
    }

    public function addLoginHistory(LoginHistory $loginHistory): static
    {
        if (!$this->loginHistories->contains($loginHistory)) {
            $this->loginHistories->add($loginHistory);
            $loginHistory->setJobless($this);
        }

        return $this;
    }

    public function removeLoginHistory(LoginHistory $loginHistory): static
    {
        if ($this->loginHistories->removeElement($loginHistory)) {
            // set the owning side to null (unless already changed)
            if ($loginHistory->getJobless() === $this) {
                $loginHistory->setJobless(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setApplicant($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getApplicant() === $this) {
                $offer->setApplicant(null);
            }
        }

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
            $cv->setCreator($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): static
    {
        if ($this->cvs->removeElement($cv)) {
            // set the owning side to null (unless already changed)
            if ($cv->getCreator() === $this) {
                $cv->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setEmployee($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getEmployee() === $this) {
                $experience->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setStudent($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getStudent() === $this) {
                $formation->setStudent(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription): static
    {
        // set the owning side of the relation if necessary
        if ($subscription->getClient() !== $this) {
            $subscription->setClient($this);
        }

        $this->subscription = $subscription;

        return $this;
    }

    public function getStrike(): ?int
    {
        return $this->strike;
    }

    public function setStrike(int $strike): static
    {
        $this->strike = $strike;

        return $this;
    }

    public function isUpdated(): ?bool
    {
        return $this->is_updated;
    }

    public function setIsUpdated(bool $is_updated): static
    {
        $this->is_updated = $is_updated;

        return $this;
    }

    public function getUpdatedNameAt(): ?\DateTimeImmutable
    {
        return $this->updated_name_at;
    }

    public function setUpdatedNameAt(\DateTimeImmutable $updated_name_at): static
    {
        $this->updated_name_at = $updated_name_at;

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
        }

        return $this;
    }

    public function removePoi(Poi $poi): static
    {
        $this->pois->removeElement($poi);

        return $this;
    }

    public function getCvCount(): ?int
    {
        return $this->cv_count;
    }

    public function setCvCount(int $cv_count): static
    {
        $this->cv_count = $cv_count;

        return $this;
    }
}
