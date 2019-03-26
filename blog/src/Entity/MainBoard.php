<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MainBoardRepository")
 */
class MainBoard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $addTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fixTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReBoard", mappedBy="mainboard", orphanRemoval=true)
     */
    private $reBoards;

    public function __construct()
    {
        $this->reBoards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAddTime(): ?\DateTimeInterface
    {
        return $this->addTime;
    }

    public function setAddTime(?\DateTimeInterface $addTime): self
    {
        $this->addTime = $addTime;

        return $this;
    }

    public function getFixTime(): ?\DateTimeInterface
    {
        return $this->fixTime;
    }

    public function setFixTime(?\DateTimeInterface $fixTime): self
    {
        $this->fixTime = $fixTime;

        return $this;
    }

    /**
     * @return Collection|ReBoard[]
     */
    public function getReBoards(): Collection
    {
        return $this->reBoards;
    }

    public function addReBoard(ReBoard $reBoard): self
    {
        if (!$this->reBoards->contains($reBoard)) {
            $this->reBoards[] = $reBoard;
            $reBoard->setMainboard($this);
        }

        return $this;
    }

    public function removeReBoard(ReBoard $reBoard): self
    {
        if ($this->reBoards->contains($reBoard)) {
            $this->reBoards->removeElement($reBoard);
            // set the owning side to null (unless already changed)
            if ($reBoard->getMainboard() === $this) {
                $reBoard->setMainboard(null);
            }
        }

        return $this;
    }
}
