<?php 
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Record;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecordDetailTest extends WebTestCase
{
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