<?php 
namespace AppBundle\Tests\AppBundle\Entity;

use AppBundle\Entity\Record;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class RecordDetailTest extends WebTestCase
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

    protected function setUp()
    {
        $this->object = new Record();
    }

    /**
     * [testGetterAndSetter Record測試SET&GET]
     */
    public function testGetterAndSetter() {
        $this->assertNull($this->object->getId());

        $date = new \DateTime();
        $this->object->setCreatedAt($date);
        $this->assertEquals($date, $this->object->getCreatedAt());

        $this->object->setUpdatedAt($date);
        $this->assertEquals($date, $this->object->getUpdatedAt());

        $this->object->setAfterMoney(0);
        $this->assertEquals(0, $this->object->getAfterMoney());

        $this->object->setSerial(0);
        $this->assertEquals(0, $this->object->getSerial());

        $this->assertNull($this->object->getUser());
    }
}