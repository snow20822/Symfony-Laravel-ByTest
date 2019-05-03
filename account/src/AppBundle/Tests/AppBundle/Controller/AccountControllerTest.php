<?php 
namespace AppBundle\Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use AppBundle\Entity\User;
use AppBundle\Entity\Record;

class AccountControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
        $this->redisClient = $kernel->getContainer()->get('snc_redis.default');
    }

     protected function tearDown()
    {
        $this->redisClient->flushAll();
    }

    /**
     * [testIndex 測試首頁頁碼小於1]
     */
    public function testIndexPageNum()
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'account.com'
            ]
        );

        $crawler = $client->request('GET', '/?page=0');

        $this->assertTrue($client->getResponse()->isRedirect('/?page=1'));
    }

    /**
     * [testAddSerialUnique 測試新增帳務資訊]
     */
    public function testAddSerialUnique()
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'account.com'
            ]
        );

        $crawler = $client->request(
            'Post',
            '/add',
            [
                'name' => 'newUser',
                'in_out' => 1,
                'description' => 'testAddSerialUnique',
                'serial' => 123
            ]
         );

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * [testAddFormSerialUnique 測試新增帳務資訊]
     */
    public function testAddFormSerialUnique()
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'account.com'
            ]
        );

        $crawler = $client->request(
            'Post',
            '/addByForm',
            [
                'form'=>
                    [
                        'name' => 'newFormUser',
                        'in_out' => 1,
                        'description' => 'testAddFormSerialUnique',
                        'serial' => 123
                    ]
            ]
         );

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * [testAddFormSerialUnique 測試無Redis紀錄狀態下新增]
    */
    public function testNotHaveRedis()
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'account.com'
            ]
        );

        $crawler = $client->request(
            'Post',
            '/addByRedis',
            [
                'name' => 'test',
                'in_out' => 1,
                'description' => 'testNotHaveRedis'
            ]
         );

        $this->assertEquals("addByRedis success", $client->getResponse()->getContent());
    }

    /**
     * [testDBALException 測試DBALException狀態]
    */
    public function testDBALException()
    {
        $lastRecord = $this->entityManager->getRepository(Record::class)->selectByArray([], 0, 1);
        $updateList = 'updateList' . $lastRecord[0]['user_id'];
        $userData = 'userData' . $lastRecord[0]['user_id'];
        $date = date("Y-m-d H:i:s");

        $updateArray = [
            'user_id' => $lastRecord[0]['user_id'],
            'in_out' => $lastRecord[0]['inOut'],
            'description' => $lastRecord[0]['description'],
            'after_money' => $lastRecord[0]['afterMoney'],
            'serial' => $lastRecord[0]['serial'],
            'created_at' => $date,
            'updated_at' => $date,
            'version' => $lastRecord[0]['version'] + 1
        ];

        $updateJson = json_encode($updateArray);
        $this->redisClient->rPush($updateList, $updateJson);
        $this->redisClient->hSet($userData, 'id', $lastRecord[0]['user_id']);
        $this->redisClient->hSet($userData, 'version', ($lastRecord[0]['version'] + 1));
        $this->redisClient->hSet($userData, 'money', $lastRecord[0]['money']);

        $input = new ArrayInput(
            [
               'command' => 'app:updateByRedis',
               'userId' => $lastRecord[0]['user_id'],
               'num' => 500
            ]
        );

        $output = new BufferedOutput();
        $this->application->run($input, $output);
        $content = $output->fetch();

        $this->assertEquals('response: update happen error', $content);
    }

    /**
     * [testVersionError 測試VersionError]
    */
    public function testVersionError()
    {
        $lastRecord = $this->entityManager->getRepository(Record::class)->selectByArray([], 0, 1);
        $updateList = 'updateList' . $lastRecord[0]['user_id'];
        $userData = 'userData' . $lastRecord[0]['user_id'];
        $date = date("Y-m-d H:i:s");

        $updateArray = [
            'user_id' => $lastRecord[0]['user_id'],
            'in_out' => $lastRecord[0]['inOut'],
            'description' => $lastRecord[0]['description'],
            'after_money' => $lastRecord[0]['afterMoney'],
            'serial' => $lastRecord[0]['serial'],
            'created_at' => $date,
            'updated_at' => $date,
            'version' => $lastRecord[0]['version']
        ];

        $updateJson = json_encode($updateArray);
        $this->redisClient->rPush($updateList, $updateJson);
        $this->redisClient->hSet($userData, 'id', $lastRecord[0]['user_id']);
        $this->redisClient->hSet($userData, 'version', $lastRecord[0]['version'] + 1);
        $this->redisClient->hSet($userData, 'money', $lastRecord[0]['money']);

        $input = new ArrayInput(
            [
               'command' => 'app:updateByRedis',
               'userId' => $lastRecord[0]['user_id'],
               'num' => 500
            ]
        );

        $output = new BufferedOutput();
        $this->application->run($input, $output);
        $content = $output->fetch();

        $this->assertEquals('response: version error: 1 <= 1', $content);
    }
}