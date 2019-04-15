<?php 
namespace Tests\AppBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserDetailTest extends WebTestCase
{
    protected function setUp()
    {
        $this->object = new User();
    }

    /**
     * [testGetterAndSetter User測試SET&GET]
     */
    public function testGetterAndSetter() {

        $this->assertNull($this->object->getId());

        $this->assertNull($this->object->getVersion());

        $this->object->setName("test");
        $this->assertEquals("test", $this->object->getName());

        $money = 1;
        $this->object->setMoney($money);
        $this->assertEquals($money, $this->object->getMoney());
    }
}