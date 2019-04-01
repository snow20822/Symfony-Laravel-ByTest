<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 *
 * @ORM\Table(name="record")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecordRepository")
 */
class Record
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="in_out", type="float")
     */
    private $inOut;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var float
     *
     * @ORM\Column(name="after_money", type="float")
     */
    private $afterMoney;

    /**
     * @var int
     *
     * @ORM\Column(name="serial", type="bigint", unique=true)
     */
    private $serial;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="records")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set inOut
     *
     * @param float $inOut
     *
     * @return Record
     */
    public function setInOut($inOut)
    {
        $this->inOut = $inOut;

        return $this;
    }

    /**
     * Get inOut
     *
     * @return float
     */
    public function getInOut()
    {
        return $this->inOut;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Record
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Record
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Record
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set afterMoney
     *
     * @param float $afterMoney
     *
     * @return User
     */
    public function setAfterMoney($afterMoney)
    {
        $this->afterMoney = $afterMoney;

        return $this;
    }

    /**
     * Get afterMoney
     *
     * @return float
     */
    public function getAfterMoney()
    {
        return $this->afterMoney;
    }

    /**
     * Set serial
     *
     * @param int $serial
     *
     * @return User
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial
     *
     * @return int
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Record
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
