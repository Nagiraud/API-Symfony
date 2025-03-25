<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event','artist'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event','artist'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['event','artist'])]
    private ?\DateTimeInterface $date = null;

    //clé étrangére référant l'artiste concerné
    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['event'])]
    private ?Artist $artist = null;

    //reférence les utilisateurs qui ont ajouté l'évent

    #[Groups(['event'])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $Follower;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'createdEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getFollower(): Collection
    {
        return $this->Follower;
    }

    public function addUser(User $user): static
    {
        if (!$this->Follower->contains($user)) {
            $this->Follower->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static{
        $this->Follower->removeElement($user);
        return $this;

    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }
}
