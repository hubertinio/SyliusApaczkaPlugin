<?php

namespace Hubertinio\SyliusApaczkaPlugin\Model\Order;

class Options
{
    public function __construct(
        public int $smsNotification = 0,
        public int $rod = 0,
        public int $saturdayDelivery = 0,
        public int $timeWindowDelivery = 0,
        public int $handleWithCare = 0,
    ) {}

    public function toArray(): array
    {
        return [
            '31' => $this->smsNotification,
            '11' => $this->rod,
            '19' => $this->saturdayDelivery,
            '25' => $this->timeWindowDelivery,
            '58' => $this->handleWithCare
        ];
    }
}
