<?php

namespace App\Repository;

use App\Entity\MainBoard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MainBoard|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainBoard|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainBoard[]    findAll()
 * @method MainBoard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainBoardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MainBoard::class);
    }

    /**
     * [getIndexPageData [抓取主要留言]
     * @return [query] [分頁功能所要query]
     */
    public function getIndexPageData()
    {
        $getIndexPageData = $this->createQueryBuilder('MainBoard')->orderBy('MainBoard.id', 'DESC');

        return $getIndexPageData;
    }

}
