<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Calculator;

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Repository\OrderPosRepository;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Webmozart\Assert\Assert;

final class PerApaczkaOrderRateCalculator implements CalculatorInterface
{
    public function __construct(
        #[Autowire(service: 'hubertinio_sylius_apaczka_plugin.api.client')] private ApaczkaApiClient $apaczkaApiClient,
        #[Autowire(service: 'hubertinio_sylius_apaczka_plugin.repository.pos')] private OrderPosRepository $posRepository,
        private LoggerInterface $logger
    ) {
    }

    public function calculate(BaseShipmentInterface $subject, array $configuration): int
    {
        Assert::isInstanceOf($subject, ShipmentInterface::class);

        $channelCode = $subject->getOrder()->getChannel()->getCode();
        $orderId = $subject->getOrder()->getId();

        $pos = $this->posRepository->find((string) $orderId);

        if ($pos) {
            /**
             * @TODO api call
             */
            $this->apaczkaApiClient::$appId = $configuration['api_key'];
            $this->apaczkaApiClient::$appSecret = $configuration['api_secret'];

            $t3 = $subject->getOrder()->getShippingAddress()->getCountryCode();

            return 1244;
        }


        /**
         * @TODO api call
         */
        return 0;
    }

    public function getType(): string
    {
        return Calculator::PER_ORDER_RATE;
    }
}
