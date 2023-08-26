<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Calculator;

use Hubertinio\SyliusApaczkaPlugin\Calculator\Calculators;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Webmozart\Assert\Assert;

final class PerApaczkaOrderRateCalculator implements CalculatorInterface
{
    /**
     * @throws MissingChannelConfigurationException
     */
    public function calculate(BaseShipmentInterface $subject, array $configuration): int
    {
        Assert::isInstanceOf($subject, ShipmentInterface::class);

        $channelCode = $subject->getOrder()->getChannel()->getCode();

        if (!isset($configuration[$channelCode])) {
            throw new MissingChannelConfigurationException(sprintf(
                'Channel %s has no amount defined for shipping method %s',
                $subject->getOrder()->getChannel()->getName(),
                $subject->getMethod()->getName(),
            ));
        }

        /**
         * @TODO api call
         */
        $configuration[$channelCode]['amount'] = mt_rand(1000, 2000);

        return (int) $configuration[$channelCode]['amount'];
    }

    public function getType(): string
    {
        return Calculator::PER_ORDER_RATE;
    }
}
