<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Record;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadRecordDataTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $databasedrop = 'doctrine:database:drop --force';
        $application->run(new StringInput($databasedrop));
        $databasecreate = 'doctrine:database:create';
        $application->run(new StringInput($databasecreate));
        $schema = 'doctrine:schema:update --force';
        $application->run(new StringInput($schema));
        $fixtures = 'doctrine:fixtures:load --append';
        $application->run(new StringInput($fixtures));
    }

    /**
     * [testSelectByArray 測試用陣列抓取數據]
     */
    public function testSelectByArray()
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $date = new \DateTime();
        $selectArray = ['id' => 0];
        $limit = 1;
        $recordRepository = $entityManager->getRepository(Record::class);
        $record = $recordRepository->selectByArray($selectArray, $limit);

        $this->assertCount(0, $record);
    }
}