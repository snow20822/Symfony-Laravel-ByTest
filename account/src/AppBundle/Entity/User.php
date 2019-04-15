<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User.會員
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     * [$id 主鍵]
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * [$version 版本號]
    .* @ORM\Version
    .* @ORM\Column(name="version", type="integer")
    .*/
    private $version;

    /**
     * [$name 姓名]
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var float
     * [$money 剩餘金額]
     * @ORM\Column(name="money", type="float")
     */
    private $money;

    /**
     * [$records 帳務紀錄]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Record", mappedBy="user", orphanRemoval=true)
     */
    private $records;

        public function __construct()
    {
        $this->records = new ArrayCollection();
    }

    /**
     * [getId 抓取Id]
     * @return [int] [會員Id]
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * [getVersion 抓取版本號]
     * @return [int] [版本號]
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * [setName 設定會員姓名]
     * @param [string] $name [會員姓名]
     * @return [array] [會員資料]
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * [getName 抓取會員姓名]
     * @return [string] [會員姓名]
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * [setMoney 設定剩餘金額]
     * @param [float] $money [剩餘金額]
     * @return [array] [會員資料]
     */
    public function setMoney($money)
    {
        $this->money = $money;

        return $this;
    }

    /**
     * [getMoney 抓取剩餘金額]
     * @return [float] [剩餘金額]
     */
    public function getMoney()
    {
        return $this->money;
    }
}
