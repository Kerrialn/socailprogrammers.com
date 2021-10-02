<?php

namespace App\Entity;

use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $startsAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $endsAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $longitude;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isComplete = false;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="event", fetch="EXTRA_LAZY")
     */
    private Collection $participants;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $rsvpBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $link;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventsHosted")
     */
    private ?User $host;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isOnline;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
        $this->participants = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function getStartsAtInAgo()
    {
        return Carbon::create($this->startsAt)
            ->diffForHumans();
    }

    public function setStartsAt(\DateTimeImmutable $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeImmutable $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getIsComplete(): ?bool
    {
        return $this->isComplete;
    }

    public function setIsComplete(bool $isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(?Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setEvent($this);
        }

        return $this;
    }

    public function removeParticipant(?Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getEvent() === $this) {
                $participant->setEvent(null);
            }
        }
        return $this;
    }

    public function getIsAttending(User $user): bool
    {
        return $this->getParticipants()
            ->exists(function ($key, Participant $participant) use ($user) {
                return $participant->getUser() === $user;
            });
    }

    public function getRsvpBy(): ?\DateTimeInterface
    {
        return $this->rsvpBy;
    }

    public function getIsCurrentDateBeforeRsvpByDate(): bool
    {
        return Carbon::now()
            ->isBefore($this->rsvpBy);
    }

    public function getIsEventInProgress(): bool
    {
        return Carbon::now()
            ->isBetween($this->startsAt, $this->endsAt);
    }

    public function getIsEventFinished(): bool
    {
        return Carbon::now()
            ->isAfter($this->endsAt);
    }

    public function setRsvpBy(?\DateTimeInterface $rsvpBy): self
    {
        $this->rsvpBy = $rsvpBy;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(?UserInterface $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

        return $this;
    }

}
