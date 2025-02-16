<?php

namespace Hubertinio\SyliusApaczkaPlugin\Cli;

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ApiCommand extends Command
{
    protected ApaczkaApiClientInterface $apiClient;

    public function __construct(ApaczkaApiClientInterface $apiClient)
    {
        parent::__construct();

        $this->apiClient = $apiClient;
    }

    protected function configure(): void
    {
        $this
            ->addOption('app-id', 'i', InputOption::VALUE_REQUIRED, 'Application ID')
            ->addOption('app-secret', 'k', InputOption::VALUE_REQUIRED, 'Secret key');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $appId = $input->getOption('app-id');
        $secret = $input->getOption('app-secret');

        if (!$appId || !$secret) {
            $output->writeln('<error>Both --app-id and --app-secret options are required.</error>');
            return Command::FAILURE;
        }

        $this->apiClient::setAppId($appId);
        $this->apiClient::setAppSecret($secret);

        return Command::SUCCESS;
    }

    protected function getOrder(): array
    {
        return $order = [
            'service_id'  => 41, // endpoint: service_structure
            'address' => [
                'sender' => [
                    'country_code'       => 'PL', // Kod ISO 3166-1 alpha-2
                    'name'               => 'Rocket Design Michał Nowak',
                    'line1'              => 'Wierzbowa 33 m. 39',
                    'line2'              => '',
                    'postal_code'        => '90-245',
                    'city'               => 'Lodz',
                    'is_residential'     => 0,  // adres prywatny: 0 / 1
                    'contact_person'     => 'Michał Nowak',
                    'email'              => 'sylius@hubertmiazek.com',
                    'phone'              => '600824141',
                    'foreign_address_id' => 'LOD129M',
                ],
                'receiver' => [
                    'country_code'       => 'PL', // Kod ISO 3166-1 alpha-2
                    'name'               => 'Hubert Miazek',
                    'line1'              => 'Niciarniana 16 m. 50',
                    'line2'              => '',
                    'postal_code'        => '92-334',
                    'city'               => 'Lodz',
                    'is_residential'     => 1,  // adres prywatny: 0 / 1
                    'contact_person'     => 'Hubert Miazek',
                    'email'              => 'b2b@hubertmiazek.com',
                    'phone'              => '513671443',
                    'foreign_address_id' => 'LOD48N'  // endpoint: points
                ]
            ],
            'option'         => [
                '31' => 0, // powiadomienie sms,
                '11' => 0, // rod
                '19' => 0, // dostawa w sobotę,
                '25' => 0, // dostawa w godzinach,
                '58' => 0, // ostrożnie
            ],
            'notification' => [
                'new' => [ // Powiadomienia o utworzeniu przesyłki
                    'isReceiverEmail' => 1, // 0 / 1
                    'isReceiverSms'   => 0, // 0 / 1
                    'isSenderEmail'   => 0  // 0 / 1
                ],
                'sent' => [ // Powiadomienia o wysłaniu przesyłki
                    'isReceiverEmail' => 1, // 0 / 1
                    'isReceiverSms'   => 0, // 0 / 1
                    'isSenderEmail'   => 1, // 0 / 1
                    'isSenderSms'     => 0, // 0 / 1
                ],
                'exception' => [ // Powiadomienia o wyjątku
                    'isReceiverEmail' => 1, // 0 / 1
                    'isReceiverSms'   => 0, // 0 / 1
                    'isSenderEmail'   => 1, // 0 / 1
                    'isSenderSms'     => 0, // 0 / 1
                ],
                'delivered' => [ // Powiadomienia o doręczeniu
                    'isReceiverEmail' => 1, // 0 / 1
                    'isReceiverSms'   => 0, // 0 / 1
                    'isSenderEmail'   => 0, // 0 / 1
                    'isSenderSms'     => 0, // 0 / 1
                ]
            ],
            'shipment_value' => 9900,  // wartość w groszach
            'cod'            => [
                'amount'      => 0, // wartość w groszach
                'bankaccount' => ''
            ],
            'pickup'         => [
                'type'       => 'SELF', // endpoint: service_structure
//                'date'       => date('Y-m-d', strtotime('tomorrow')),     // Y-m-d
//                'hours_from' => '08:00',     // H:i - pickup_hours
//                'hours_to'   => '16:00'      // H:i - pickup_hours
            ],
            'shipment' => [
                [
                    'dimension1' => 10, // długość (length) cm
                    'dimension2' => 20, // szerokość (width) cm
                    'dimension3'  => 30, // wysokość (height) cm
                    'weight' => 1,  // kg
                    'is_nstd' => 0,  // 0 / 1
                    'shipment_type_code' => 'PACZKA', // endpoint: service_structure
                ],
            ],
//            'comment' => 'TEST ' . date('Y-m-d H:i:s'),
            'comment' => '',
            'content' => '',
        ];
    }
}