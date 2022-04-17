<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['accounts_list', 'account_detail'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['accounts_list', 'account_detail'])]
    #[Assert\NotBlank(message:'Saisir un nom de compte')]
    private $name;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['account_detail'])]
    private $openedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Result::class, orphanRemoval: true)]
    #[Groups(['accounts_list', 'account_detail'])]
    private $results;

    // public function __toString()
    // {
    //     return $this->results;
    // }

    public function __construct()
    {
        $this->deposit = new ArrayCollection();
        $this->withdrawal = new ArrayCollection();
        $this->result = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->openedAt = new DateTimeImmutable();
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOpenedAt(): ?\DateTimeImmutable
    {
        return $this->openedAt;
    }

    public function setOpenedAt(\DateTimeImmutable $openedAt): self
    {
        $this->openedAt = $openedAt;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setAccount($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getAccount() === $this) {
                $result->setAccount(null);
            }
        }

        return $this;
    }
}
