<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Cli;

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see https://panel.apaczka.pl/dokumentacja_api_v2.php#endpoints-points
 */
final class LoadPointsCommand extends Command
{
    protected   static $defaultName = 'sylius:shipping:apaczka:load-points';

    protected static $defaultDescription = 'Load points by type';

    private ApaczkaApiClientInterface $apiClient;

    public function __construct(ApaczkaApiClientInterface $apiClient)
    {
        parent::__construct();

        $this->apiClient = $apiClient;
    }

    protected function configure(): void
    {
        $this->addArgument(
            'type',
            InputArgument::REQUIRED,
            'One of ' . implode(', ', ApaczkaApiClient::POINTS_TYPES)
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->apiClient::points($input->getArgument('type'));
        $data = json_decode($data, true);

        $table = new Table($output);
        $table->setHeaders(['ID', 'Country', 'Zip', 'City', 'Address', 'Name']);

        foreach ($data['response']['points'] ?? [] as $pointId => $row) {
            $table->addRow([
                $row['foreign_address_id'],
                $row['address']['country_code'],
                $row['address']['postal_code'],
                $row['address']['city'],
                trim($row['address']['line1'] . ' ' . $row['address']['line2']),
                $row['name'],
            ]);
        }

        $table->render();

        return  Command::SUCCESS;
    }
}