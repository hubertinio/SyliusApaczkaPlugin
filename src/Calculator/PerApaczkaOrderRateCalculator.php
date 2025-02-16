<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Calculator;

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Model\Order as ApaczkaOrder;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\Address as ApaczkaAddress;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\Options as ApaczkaOptions;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\Pickup as ApaczkaPickup;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\Shipment as ApaczkaShipment;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\COD as ApaczkaCOD;
use Hubertinio\SyliusApaczkaPlugin\Repository\OrderPosRepository;
use Sylius\Component\Core\Model\Address;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Webmozart\Assert\Assert;

final class PerApaczkaOrderRateCalculator implements CalculatorInterface
{
    public function __construct(
        #[Autowire(service: 'hubertinio_sylius_apaczka_plugin.api.client')] private ApaczkaApiClient $apaczkaApiClient,
        #[Autowire(service: 'hubertinio_sylius_apaczka_plugin.repository.pos')] private OrderPosRepository $posRepository,
        #[Autowire(service: 'sylius.factory.address')] private \Sylius\Component\Core\Factory\AddressFactory $addressFactory,
        #[Autowire(service: 'sylius.repository.address')] private object $addressRepository,
        #[Autowire(service: 'sylius.repository.order')] private object $orderRepository,
        private LoggerInterface $logger
    ) {
    }

    public function calculate(BaseShipmentInterface $subject, array $configuration): int
    {
        Assert::isInstanceOf($subject, ShipmentInterface::class);

        $orderId = $subject->getOrder()->getId();
        $pos = $this->posRepository->find((string) $orderId);

        if ($pos) {
            /**
             * @TODO dynamiczny serviceId
             */
            $serviceId = 41;
            $this->apaczkaApiClient::setAppId($configuration['api_key']);
            $this->apaczkaApiClient::setAppSecret($configuration['api_secret']);

            /** @var Address $shippingAddress */
            $order = $subject->getOrder();
            $shippingAddress = $order->getShippingAddress();

            if (
                $subject->getMethod()?->getCode() === 'apaczka'
                && $shippingAddress->getFirstName() !== $pos['operator']
                && $shippingAddress->getLastName() !== $pos['code']
            ) {
                $newShippingAddress = new \Sylius\Component\Core\Model\Address();
                $newShippingAddress->setFirstName($pos['operator']);
                $newShippingAddress->setLastName($pos['code']);
                $newShippingAddress->setProvinceName($pos['province']);
                $newShippingAddress->setCity($pos['city']);
                $newShippingAddress->setStreet($pos['street']);
                $newShippingAddress->setPostcode($pos['postalCode']);
                $newShippingAddress->setCountryCode('PL');
                $newShippingAddress->setCustomer($shippingAddress->getCustomer());
                $this->addressRepository->add($newShippingAddress);

                $order->setShippingAddress($shippingAddress);
                $this->orderRepository->add($order);
            }

            $aOrder = new ApaczkaOrder(
                serviceId: $serviceId,
                receiver: new ApaczkaAddress(
                    countryCode: 'PL',
                    name: 'Hubert Miazek',
                    line1: 'Niciarniana 16 m. 50',
                    postalCode: '92-334',
                    city: 'Lodz',
                    contactPerson: 'Hubert Miazek',
                    email: 'b2b@hubertmiazek.com',
                    phone: '513671443',
                    foreignAddressId: 'LOD48N',
                    isResidential: false
                ),
                options: new ApaczkaOptions,
                shipmentValue: 9900,
                cod: new ApaczkaCOD,
                pickup: new ApaczkaPickup(),
                shipment: [
                    new ApaczkaShipment(
                        lengthInCm: 10,
                        widthInCm: 20,
                        heightInCm: 30,
                        weightInKg: 1,
                    )
                ],
                comment: '',
                content: ''
            );

            $aOrderArray = $aOrder->toArray();
            $data = $this->apaczkaApiClient->order_valuation($aOrderArray);

            $data = json_decode($data, true);

            /**
             * @TODO calculate price from PLN
             */
            return $data["response"]["price_table"][$serviceId]["price_gross"] ?? 0;
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
