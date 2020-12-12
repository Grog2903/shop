<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=OrderSet::class, mappedBy="orderU")
     */
    private $orderSets;

    public function __construct()
    {
        $this->orderSets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|OrderSet[]
     */
    public function getOrderSets(): Collection
    {
        return $this->orderSets;
    }

    public function addOrderSet(OrderSet $orderSet): self
    {
        if (!$this->orderSets->contains($orderSet)) {
            $this->orderSets[] = $orderSet;
            $orderSet->setOrderU($this);
        }

        return $this;
    }

    public function removeOrderSet(OrderSet $orderSet): self
    {
        if ($this->orderSets->removeElement($orderSet)) {
            // set the owning side to null (unless already changed)
            if ($orderSet->getOrderU() === $this) {
                $orderSet->setOrderU(null);
            }
        }

        return $this;
    }
}
