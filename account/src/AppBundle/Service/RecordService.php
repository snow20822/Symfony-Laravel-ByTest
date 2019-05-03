<?php
namespace AppBundle\Service;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class RecordService extends Controller
{
    private $entityManager;

    private $redis;

    public function __construct(EntityManager $entityManager, $redis)
    {
        $this->entityManager = $entityManager;
        $this->redis = $redis;
    }

    /**
     * [updateByRedis 使用Redis更新帳務]
     * @param  [int] $userId [會員id]
     * @param  [int] $num [更新次數]
     * @return [text] [更新結果]
     */
    public function updateByRedis($userId, $num)
    {
        $client = $this->redis;
        $response = "";
        $log = new Logger('updateByRedis');
        $log->pushHandler(new StreamHandler('var/logs/Redis.log', Logger::INFO));
        $this->connection = $this->entityManager->getConnection();
        $userData = 'userData' . $userId;
        $updateList = 'updateList' . $userId;
        $checkEXISTS = $client->exists($updateList);
        if (!$checkEXISTS) {
            $response = 'no userData need update';
            $log->addInfo($response);
            return $response;
        }

        $total = $client->lLen($updateList);
        $user = $this->connection->fetchAssoc('SELECT * FROM user WHERE id = ?', [$userId]);

        if ($total < $num) {
            $num = $total;
        }

        $rangeNum = $num-1;
        $updateData = $client->lRange($updateList, 0, $rangeNum);
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $allupdataArray = [];

            for ($i=0; $i < $num; $i++) {
                $insertData = json_decode($updateData[$i], true);

                if ($insertData['version'] <= $user['version']) {
                    $response = 'version error: ' . $insertData['version'] . ' <= ' . $user['version'];
                    $log->addInfo($response);

                    return $response;
                }

                $updataArray = [
                    'user_id' => $insertData['user_id'],
                    'in_out' => $insertData['in_out'],
                    'description' => $insertData['description'],
                    'after_money' => $insertData['after_money'],
                    'serial' => $insertData['serial'],
                    'created_at' => $insertData['created_at'],
                    'updated_at' => $insertData['updated_at']
                ];
                array_push($allupdataArray, $updataArray);
            }

            $placeholders = [];
            $values = [];
            $types = [];

            foreach ($allupdataArray as $columnName => $value) {
                $placeholders[] = '(?)';
                $values[] = array_values($value);
                $types[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
            }

            $this->connection->executeUpdate(
                'INSERT INTO `record` (`user_id`, `in_out`, `description`, `after_money`, `serial`, `created_at`, `updated_at`) VALUES ' . implode(', ', $placeholders),
                $values,
                $types
            );
            
            $this->connection->update('user',
                [
                    'version' => $user['version'] + $num,
                    'money' => $client->hGet($userData, 'money')
                ],
                [
                    'id' => $userId
                ]
            );
            $this->entityManager->getConnection()->commit();
        } catch (DBALException $e) {
            $this->entityManager->getConnection()->rollback();
            $response = 'update happen error';
            $log->addInfo($response);

            return $response;
        }

        $client->lTrim($updateList, $num, -1);
        $log->addInfo(json_encode($updateData));
        $response = 'update ' . $num . ' times success';

        return $response;
    }
}
