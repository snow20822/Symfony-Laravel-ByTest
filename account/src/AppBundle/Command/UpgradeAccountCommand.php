<?php 
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\Record;
use AppBundle\Service\RecordService;

class UpgradeAccountCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:updateByRedis';

    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Upgrade Account.')
            ->setHelp('This command for update account')
            ->addArgument('userId', InputArgument::REQUIRED, 'Id of update user.')
            ->addArgument('num', InputArgument::REQUIRED, 'How many loop want');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        $num = $input->getArgument('num');

        $response = $this->recordService->updateByRedis($userId, $num);

        $output->write('response: ' . $response);
    }
}