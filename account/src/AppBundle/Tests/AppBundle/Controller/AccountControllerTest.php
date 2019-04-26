<?php 
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Record;

class AccountControllerTest extends WebTestCase
{
    protected function setUp()
    {
        //clear redis
        $this->redisClient = RedisAdapter::createConnection('redis://localhost:6379');
        $this->redisClient->FLUSHALL();

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
    }

    /**
     * [testIndex 測試首頁頁碼小於1]
     */
    public function testIndexPageNum()
    {
        $client = static::createClient( 
        [],
        [
            'HTTP_HOST' => 'account.com',
        ]);

        $crawler = $client->request('GET', '/?page=0');

        $this->assertTrue($client->getResponse()->isRedirect('/?page=1'));
    }

    /**
     * [testAddSerialUnique 測試新增帳務資訊]
     * @param  [int] $serial [流水號]
     */
    public function testAddSerialUnique()
    {
        $client = static::createClient( 
        [],
        [
            'HTTP_HOST' => 'account.com',
        ]);

        $crawler = $client->request('Post', '/add', ['name' => 'newUser', 'in_out' => 1, 'description' => 'testAddSerialUnique', 'serial' => 123]);

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * [testAddFormSerialUnique 測試新增帳務資訊]
     * @param  [int] $serial [流水號]
     */
    public function testAddFormSerialUnique()
    {
        $client = static::createClient( 
        [],
        [
            'HTTP_HOST' => 'account.com',
        ]);

        $crawler = $client->request('Post', '/addByForm', ['form'=>['name' => 'newFormUser', 'in_out' => 1, 'description' => 'testAddFormSerialUnique', 'serial' => 123]]);

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
            'HTTP_HOST' => 'account.com',
        ]);

        $crawler = $client->request('Post', '/addByRedis', ['name' => 'test', 'in_out' => 1, 'description' => 'testNotHaveRedis']);

        $this->assertEquals("addByRedis success", $client->getResponse()->getContent());
    }

    /**
     * [testDBALException 測試DBALException狀態]
    */
    public function testDBALException()
    {
        $lastRecord = $this->entityManager->getRepository(Record::class)->selectByArray([], 1);
        $updateList = 'updateList'.$lastRecord[0]['user']['id'];
        $userData = 'userData'.$lastRecord[0]['user']['id'];
        
        $date = date("Y-m-d H:i:s");
        $updateArray = [
            'user_id' => $lastRecord[0]['user']['id'],
            'in_out' => $lastRecord[0]['inOut'],
            'description' => $lastRecord[0]['description'],
            'after_money' => $lastRecord[0]['afterMoney'],
            'serial' => $lastRecord[0]['serial'],
            'created_at' => $date,
            'updated_at' => $date,
            'version' => $lastRecord[0]['user']['version']+1
        ];
        $updateJson = json_encode($updateArray);
        $this->redisClient->RPUSH($updateList, $updateJson);
        $this->redisClient->HSET($userData, 'id', $lastRecord[0]['user']['id']);
        $this->redisClient->HSET($userData, 'version', ($lastRecord[0]['user']['version']+1));
        $this->redisClient->HSET($userData, 'money', $lastRecord[0]['user']['money']);

        $input = new ArrayInput([
           'command' => 'app:updateByRedis',
           'userId' => $lastRecord[0]['user']['id'],
           'num' => 500,
        ]);
        $output = new BufferedOutput();
        $this->application->run($input, $output);
        $content = $output->fetch();

        $this->assertEquals('response: update happen error', $content);
        $this->entityManager->flush();
    }

    /**
     * [testVersionError 測試VersionError]
    */
    public function testVersionError()
    {
        $lastRecord = $this->entityManager->getRepository(Record::class)->selectByArray([], 1);
        $updateList = 'updateList'.$lastRecord[0]['user']['id'];
        $userData = 'userData'.$lastRecord[0]['user']['id'];
        $date = date("Y-m-d H:i:s");
        $updateArray = [
            'user_id' => $lastRecord[0]['user']['id'],
            'in_out' => $lastRecord[0]['inOut'],
            'description' => $lastRecord[0]['description'],
            'after_money' => $lastRecord[0]['afterMoney'],
            'serial' => $lastRecord[0]['serial'],
            'created_at' => $date,
            'updated_at' => $date,
            'version' => $lastRecord[0]['user']['version']
        ];
        $updateJson = json_encode($updateArray);
        $this->redisClient->RPUSH($updateList, $updateJson);
        $this->redisClient->HSET($userData, 'id', $lastRecord[0]['user']['id']);
        $this->redisClient->HSET($userData, 'version', $lastRecord[0]['user']['version']+1);
        $this->redisClient->HSET($userData, 'money', $lastRecord[0]['user']['money']);

        $input = new ArrayInput([
           'command' => 'app:updateByRedis',
           'userId' => $lastRecord[0]['user']['id'],
           'num' => 500,
        ]);
        $output = new BufferedOutput();
        $this->application->run($input, $output);
        $content = $output->fetch();

        $this->assertEquals('response: version error: 1 <= 1', $content);
        $this->entityManager->flush();
    }
}