<?php 
namespace AppBundle\Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use AppBundle\Entity\Record;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadRecordData extends Fixture implements OrderedFixtureInterface
{
    /**
     * [load 新增帳務紀錄]
     */
    public function load(ObjectManager $manager)
    {
        $date = new \DateTime('now');
        $User = $manager->getRepository(User::class)->findOneBy(['name' => 'test']);
        $Record = new Record();
        $Record->setInOut(1);
        $Record->setDescription('RecordFixtures');
        $Record->setCreatedAt($date);
        $Record->setUpdatedAt($date);
        $Record->setAfterMoney(1);
        $Record->setSerial(123);
        $Record->setUser($User);
        
        $manager->persist($Record);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}