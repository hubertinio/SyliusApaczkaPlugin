<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Model;

use Hubertinio\SyliusApaczkaPlugin\Model\Order\Address;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\COD;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\Options;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\Pickup;
use Hubertinio\SyliusApaczkaPlugin\Model\Order\Shipment;

final class Order
{
    public function __construct(
        public int $serviceId,
        public Address $receiver,
        public Options $options,
        public int $shipmentValue,
        public COD $cod,
        public Pickup $pickup,
        /** @var Shipment[] */
        public array $shipment,
        public string $comment = '',
        public string $content = ''
    ) {}

    public function toArray(): array
    {
        return [
            'service_id' => $this->serviceId,
            'address' => [
                'receiver' => $this->receiver->toArray()
            ],
            'options' => $this->options->toArray(),
            'shipment_value' => $this->shipmentValue,
            'cod' => $this->cod->toArray(),
            'pickup' => $this->pickup->toArray(),
            'shipment' => array_map(fn($s) => $s->toArray(), $this->shipment),
            'comment' => $this->comment,
            'content' => $this->content
        ];
    }
}
