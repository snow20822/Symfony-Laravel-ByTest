<?php 
namespace Tests\AppBundle\Command;

use AppBundle\Command\CreateUserCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:updateByRedis');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'userId' => 103,
            'num' => 1,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertEquals('response: no userData need update', $output);
    }
}