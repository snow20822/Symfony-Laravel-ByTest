<?php 
namespace AppBundle\Tests\AppBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use AppBundle\Entity\User;
use AppBundle\Entity\Record;
use Symfony\Component\Console\Input\StringInput;

class AccountFunctionalTest extends WebTestCase
{
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
    }

    /**
     * [testIndex 測試首頁]
     */
    public function testIndex()
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'account.com'
            ]
        );

        $crawler = $client->request('GET', '/?page=5000');

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * [testAdd 測試新增帳務資訊]
     * @param [string] $name [姓名]
     * @param [float] $in_out [變動金額]
     * @param [text] $description [註解]
     * @dataProvider additionProvider
     */
    public function testAdd($name, $in_out, $description)
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'account.com'
            ]
        );

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Send')->form();
        $form['name'] = $name;
        $form['in_out'] = $in_out;
        $form['description'] = $description;
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/?page=1'));
    }

    /**
     * [additionProvider 測試新增所用參數]
     * @return [array] [測試用參數]
     */
    static function additionProvider()
    {
        return [
             ['test', 3, 'testAdd'],
             ['test', -9999999999, 'testAdd']
        ];
    }

    /**
     * [testSymfonyFormPost 測試新增帳務資訊ByForm]
     * @param [string] $name [姓名]
     * @param [float] $in_out [變動金額]
     * @param [text] $description [註解]
     * @dataProvider addByFormProvider
     */
    public function testSymfonyFormPost($name, $in_out, $description)
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'account.com'
            ]
        );

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('form[save]')->form();
        $form['form[name]'] = $name;
        $form['form[in_out]'] = $in_out;
        $form['form[description]'] = $description;
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/?page=1'));
    }

    /**
     * [addByFormProvider 測試新增帳務資訊ByForm所用參數]
     * @return [array] [測試新增帳務資訊ByForm用參數]
     */
    static function addByFormProvider()
    {
        return [
             ['test', 3, 'testSymfonyFormPost'],
             ['test', -9999999999, 'testSymfonyFormPost']
        ];
    }

    /**
     * [testAddByRedisMoney 測試扣款完<0]
     */
    public function testAddByRedisMoney()
    {
        $lastRecord = $this->entityManager->getRepository(Record::class)->selectByArray(['id' => 1], 0, 1);
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
                'name' => $lastRecord[0]['name'],
                'in_out' => -100,
                'description' => 'testAddByRedis'
            ]
        );

         $this->assertEquals("Insufficient balance", $client->getResponse()->getContent());
    }

    /**
     * [testAddByRedis 測試新增帳務資訊]
     */
    public function testAddByRedis()
    {
        $lastRecord = $this->entityManager->getRepository(Record::class)->selectByArray(['id' => 1], 0, 1);
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
                'name' => $lastRecord[0]['name'],
                'in_out' => 1,
                'description' => 'testAddByRedis'
            ]
        );

        $this->assertEquals("addByRedis success", $client->getResponse()->getContent());

        $input = new ArrayInput(
            [
               'command' => 'app:updateByRedis',
               'userId' => $lastRecord[0]['user_id'],
               'num' => 1,
            ]
        );

        $output = new BufferedOutput();
        $this->application->run($input, $output);
        $content = $output->fetch();
        $newData = $this->entityManager->getRepository(Record::class)->selectByArray(['id' => 1], 0, 1);
        $newVersion = $lastRecord[0]['version']+1;
        $this->assertEquals($newVersion, $newData[0]['version']);

        $this->assertEquals('response: update 1 times success', $content);
    }

    public static function tearDownAfterClass()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $databasedrop = 'doctrine:database:drop --force';
        $application->run(new StringInput($databasedrop));
    }
}