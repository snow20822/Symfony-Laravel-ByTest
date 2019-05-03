<?php

namespace AppBundle\Repository;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Doctrine\ORM\EntityRepository;

class RecordRepository extends EntityRepository
{
    /**
     * [selectByArray [抓取帳單紀錄ByArray]
     * @param  [array] $selectArray [搜尋陣列內容]
     * @param  [integer] $firstResult [起始筆數]
     * @param  [integer] $maxResults [總筆數]
     * @return [array] [帳單紀錄]
     */
    public function selectByArray($selectArray, $firstResult = NULL, $maxResults = NULL)
    {
        $query = $this->createQueryBuilder('Record');

        $query->select('Record.id');
        $query->addSelect('Record.inOut');
        $query->addSelect('Record.description');
        $query->addSelect('Record.createdAt');
        $query->addSelect('Record.updatedAt');
        $query->addSelect('Record.afterMoney');
        $query->addSelect('Record.serial');

        foreach ($selectArray as $key => $value) {
            $query->andWhere('Record.' . $key . ' = :' . $key . '');
            $query->setParameter('' . $key . '', $value);
        }

        $query->leftJoin('Record.user', 'User');
        $query->addSelect('User.id as user_id');
        $query->addSelect('User.version');
        $query->addSelect('User.name');
        $query->addSelect('User.money');

        if (!is_null($firstResult)) {
            $query->setFirstResult($firstResult);
        }

        if (!is_null($maxResults)) {
            $query->setMaxResults($maxResults);
        }

        $query->orderBy('Record.id', 'DESC');

        return $query->getQuery()->getArrayResult();
    }
}
