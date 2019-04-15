<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Record;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;;

class LoadRecordDataTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * [testSelectByArray 測試用陣列抓取數據]
     */
    public function testSelectByArray()
    {
        $date = new \DateTime();
        $selectArray = ['id' => 0];
        $limit = 1;
        $recordRepository = $this->entityManager->getRepository(Record::class);
        $record = $recordRepository->selectByArray($selectArray, $limit);

        $this->assertCount(0, $record);
    }
}