<?php 
namespace AppBundle\DataFixtures;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * [load 新增會員紀錄]
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('test');
        $user->setMoney(1);
        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}