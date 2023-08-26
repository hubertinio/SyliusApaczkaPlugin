<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Cli;

use Hubertinio\SyliusApaczkaPlugin\Service\ApaczkaApiClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PingCommand extends Command
{
    protected   static $defaultName = 'sylius:shipping:apaczka:ping';

            protected static $defaultDescription = 'Check your API credentials';

    private ApaczkaApiClient $apiClient;

    public function __construct(ApaczkaApiClient $apiClient)
    {
        parent::__construct();

        $this->apiClient = $apiClient;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('app-id', InputArgument::REQUIRED, 'Your app ID.')
            ->addArgument('app-secret', InputArgument::REQUIRED, 'Your app secret token.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $appId = $input->getArgument('app-id');
        $appSecret = $input->getArgument('app-secret');

                        $this->apiClient::setAppId($appId);
                        $this->apiClient::setAppSecret($appSecret);

        $data = $this->apiClient->service_structure ();
        $output->writeln (print_r($data));

        return  Command::SUCCESS;
    }
}