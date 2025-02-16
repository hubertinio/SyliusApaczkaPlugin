<?php

namespace Hubertinio\SyliusApaczkaPlugin\Model\Order;

class Shipment
{
    public const SHIPMENT_TYPE_CODE_PACKAGE = 'PACZKA';

    public function __construct(
        public int $lengthInCm = 10,
        public int $widthInCm = 20,
        public int $heightInCm = 30,
        public int $weightInKg = 1,
        public bool $isNstd = false,
        public string $shipmentTypeCode = self::SHIPMENT_TYPE_CODE_PACKAGE,
    ) {}

    public function toArray(): array
    {
        return [
            'dimension1' => $this->lengthInCm,
            'dimension2' => $this->widthInCm,
            'dimension3' => $this->heightInCm,
            'weight' => $this->weightInKg,
            'is_nstd' => (int) $this->isNstd,
            'shipment_type_code' => $this->shipmentTypeCode
        ];
    }
}
