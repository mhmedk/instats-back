<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['accounts_list', 'account_detail'])]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['accounts_list', 'account_detail'])]
    private $transaction_at;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['accounts_list', 'account_detail'])]
    private $dayResult;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['accounts_list', 'account_detail'])]
    private $deposit;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['accounts_list', 'account_detail'])]
    private $withdrawal;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionAt(): ?\DateTimeImmutable
    {
        return $this->transaction_at;
    }

    public function setTransactionAt(\DateTimeImmutable $transaction_at): self
    {
        $this->transaction_at = $transaction_at;

        return $this;
    }

    public function getDayResult(): ?float
    {
        return $this->dayResult;
    }

    public function setDayResult(?float $dayResult): self
    {
        $this->dayResult = $dayResult;

        return $this;
    }

    public function getDeposit(): ?float
    {
        return $this->deposit;
    }

    public function setDeposit(?float $deposit): self
    {
        $this->deposit = $deposit;

        return $this;
    }

    public function getWithdrawal(): ?float
    {
        return $this->withdrawal;
    }

    public function setWithdrawal(?float $withdrawal): self
    {
        $this->withdrawal = $withdrawal;

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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
