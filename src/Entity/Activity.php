<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 * @ORM\Table(name="activity", indexes={@ORM\Index(columns={"address", "description"}, flags={"fulltext"})})
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @Gedmo\Slug(fields={"address"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /** 
     * 
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $participantsMax;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $participantsNumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $prerequisite;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $profile;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favorite")
     */
    private $favorite;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=RegisterActivity::class, mappedBy="activity")
     */
    private $registerActivities;

    public function __construct()
    {
        $this->favorite = new ArrayCollection();
        $this->registerActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get CreatedAt
     *
     * @return \DateTime 
     */

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getParticipantsMax(): ?int
    {
        return $this->participantsMax;
    }

    public function setParticipantsMax(int $participantsMax): self
    {
        $this->participantsMax = $participantsMax;

        return $this;
    }

    public function getParticipantsNumber(): ?int
    {
        return $this->participantsNumber;
    }

    public function setParticipantsNumber(int $participantsNumber): self
    {
        $this->participantsNumber = $participantsNumber;

        return $this;
    }

    public function getPrerequisite(): ?string
    {
        return $this->prerequisite;
    }

    public function setPrerequisite(?string $prerequisite): self
    {
        $this->prerequisite = $prerequisite;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(User $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(User $favorite): self
    {
        $this->favorite->removeElement($favorite);

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|RegisterActivity[]
     */
    public function getRegisterActivities(): Collection
    {
        return $this->registerActivities;
    }

    public function addRegisterActivity(RegisterActivity $registerActivity): self
    {
        if (!$this->registerActivities->contains($registerActivity)) {
            $this->registerActivities[] = $registerActivity;
            $registerActivity->setActivity($this);
        }

        return $this;
    }

    public function removeRegisterActivity(RegisterActivity $registerActivity): self
    {
        if ($this->registerActivities->removeElement($registerActivity)) {
            // set the owning side to null (unless already changed)
            if ($registerActivity->getActivity() === $this) {
                $registerActivity->setActivity(null);
            }
        }

        return $this;
    }
}
