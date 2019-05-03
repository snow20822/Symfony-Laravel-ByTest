<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record.帳務資料
 * @ORM\Table(name = "record")
 * @ORM\Entity(repositoryClass = "AppBundle\Repository\RecordRepository")
 */
class Record
{
    /**
     * [$id 主鍵]
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */

    private $id;

    /**
     * [$inOut 變動金額]
     * @ORM\Column(name = "in_out", type = "float")
     */
    private $inOut;

    /**
     * [$description 註解]
     * @ORM\Column(name = "description", type = "text", nullable = true)
     */
    private $description;

    /**
     * [$createdAt 建立時間]
     * @ORM\Column(name = "created_at", type = "datetime")
     */
    private $createdAt;

    /**
     * [$updatedAt 變動時間]
     * @ORM\Column(name = "updated_at", type = "datetime")
     */
    private $updatedAt;

    /**
     * [$afterMoney 變動後金額]
     * @ORM\Column(name = "after_money", type = "float")
     */
    private $afterMoney;

    /**
     * [$serial 流水號]
     * @ORM\Column(name = "serial", type = "bigint", unique = true)
     */
    private $serial;

    /**
    .* [$user 會員資料]
     * @ORM\ManyToOne(targetEntity = "AppBundle\Entity\User", inversedBy = "records")
     * @ORM\JoinColumn(nullable = false)
     */
    private $user;


    /**
     * [getId 抓取Id]
     * @return [int] [Id]
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * [setInOut 設定變動金額]
     * @param [float] $inOut [變動金額]
     * @return [array] [帳務資料]
     */
    public function setInOut($inOut)
    {
        $this->inOut = $inOut;

        return $this;
    }

    /**
     * [getInOut 抓取變動金額]
     * @return [float] [變動金額]
     */
    public function getInOut()
    {
        return $this->inOut;
    }

    /**
     * [setDescription 設定註解]
     * @param [text] $description [註解]
     * @return [array] [帳務資料]
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * [getDescription 抓取註解]
     * @return [text] [註解]
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * [setCreatedAt 設定建立時間]
     * @param [datetime] $createdAt [建立時間]
     * @return [array] [帳務資料]
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * [getCreatedAt 抓取建立時間]
     * @return [datetime] [建立時間]
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * [setCreatedAt 設定變動時間]
     * @param [datetime] $createdAt [變動時間]
     * @return [array] [帳務資料]
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * [getCreatedAt 抓取變動時間]
     * @return [datetime] [變動時間]
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * [setAfterMoney 設定變動後金額]
     * @param [float] $afterMoney [變動後金額]
     * @return [array] [帳務資料]
     */
    public function setAfterMoney($afterMoney)
    {
        $this->afterMoney = $afterMoney;

        return $this;
    }

    /**
     * [getAfterMoney 抓取變動後金額]
     * @return [float] [變動後金額]
     */
    public function getAfterMoney()
    {
        return $this->afterMoney;
    }

    /**
     * [setSerial 設定流水號]
     * @param [bigint] $serial [流水號]
     * @return [array] [帳務資料]
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * [getSerial 抓取流水號]
     * @return [bigint] [流水號]
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * [setUser 設定會員資料]
     * @param \AppBundle\Entity\User $user [會員資料]
     * @return [array] [帳務資料]
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * [getUser 抓取會員資料]
     * @return [array] [會員資料]
     */
    public function getUser()
    {
        return $this->user;
    }

    protected $name;

    /**
     * [getName 抓取會員姓名]
     * @return [string] [會員姓名]
     */
    public function getName()
    {
        return $this->name;
    }
}
