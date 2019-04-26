<?php
namespace AppBundle\Service;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use AppBundle\Entity\User;
use AppBundle\Entity\Record;

class RecordService extends Controller
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * [updateByRedis 使用Redis更新帳務]
     * @param  [int] $userId [會員id]
     * @param  [int] $num [更新次數]
     * @return [text] [更新結果]
     */
    public function updateByRedis($userId, $num)
    {
        $response = "";
        // create a log channel
        $log = new Logger('updateByRedis');
        $log->pushHandler(new StreamHandler('var/logs/Redis.log', Logger::INFO));
        $this->connection = $this->entityManager->getConnection();
        $userData = 'userData'.$userId;
        $updateList = 'updateList'.$userId;
        $client = RedisAdapter::createConnection('redis://localhost:6379');
        $checkEXISTS = $client->EXISTS($updateList);
        if(!$checkEXISTS){
            $response = 'no userData need update';
            $log->addInfo($response);
            return $response;
        }
        $total = $client->LLEN($updateList);
        $user = $this->connection->fetchAssoc('SELECT * FROM user WHERE id = ?', [$userId]);

        //check num
        if ($total < $num) {
            $num = $total;
        }
        $rangeNum = $num-1;
        $updateData = $client->LRANGE($updateList, 0, $rangeNum);
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $allupdataArray = [];
            for ($i=0; $i < $num; $i++) {
                $insertData = json_decode($updateData[$i], true);
                //更新前驗證版本號大於資料庫
                if ($insertData['version'] <= $user['version']) {
                    $response = 'version error: '.$insertData['version'].' <= '.$user['version'];
                    $log->addInfo($response);
                    return $response;
                }
                $updataArray =[
                    'user_id' => $insertData['user_id'],
                    'in_out' => $insertData['in_out'],
                    'description' => $insertData['description'],
                    'after_money' => $insertData['after_money'],
                    'serial' => $insertData['serial'],
                    'created_at' => $insertData['created_at'],
                    'updated_at' => $insertData['updated_at']
                ];
                array_push($allupdataArray,$updataArray);
            }
            //更新時直接一次新增所有紀錄
            $placeholders = [];
            $values = [];
            $types = [];

            foreach ($allupdataArray as $columnName => $value) {
                $placeholders[] = '(?)';
                $values[] = array_values($value);
                $types[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }

            $this->connection->executeUpdate(
                'INSERT INTO `record` (`user_id`, `in_out`, `description`, `after_money`, `serial`, `created_at`, `updated_at`)  VALUES ' . implode(', ', $placeholders),
                $values,
                $types
            );
            
            $this->connection->update('user',
                ['version' => $user['version']+$num,
                'money' => $client->HGET($userData, 'money')],
                ['id' => $userId]
            );
            $this->entityManager->getConnection()->commit();
        } catch (DBALException $e) {
            $this->entityManager->getConnection()->rollback();
            $response = 'update happen error';
            $log->addInfo($response);
            return $response;
        }
        //清除已寫入完成資料
        $client->LTRIM($updateList, $num, -1);
        // add records to the log
        $log->addInfo(json_encode($updateData));
        $response = 'update '.$num.' times success';
        return $response;
    }
}
