<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReBoardRepository")
 */
class ReBoard
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
     * @ORM\ManyToOne(targetEntity="App\Entity\MainBoard", inversedBy="reBoards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mainboard;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainboard(): ?MainBoard
    {
        return $this->mainboard;
    }

    public function setMainboard(?MainBoard $mainboard): self
    {
        $this->mainboard = $mainboard;

        return $this;
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
}
