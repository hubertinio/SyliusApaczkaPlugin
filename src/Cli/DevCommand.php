<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Cli;

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DevCommand extends Command
{
    protected   static $defaultName = 'sylius:shipping:apaczka:dev';

            protected static $defaultDescription = 'Dev API tests';

    private ApaczkaApiClientInterface $apiClient;

    public function __construct(ApaczkaApiClientInterface $apiClient)
    {
        parent::__construct();

        $this->apiClient = $apiClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->apiClient::service_structure ();
        $output->writeln (print_r($data));

        return  Command::SUCCESS;
    }
}