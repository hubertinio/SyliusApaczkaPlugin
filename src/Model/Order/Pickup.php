<?php

namespace Hubertinio\SyliusApaczkaPlugin\Model\Order;

class Pickup
{
    public const TYPE_SELF = 'SELF';

    public function __construct(
        public string $type = self::TYPE_SELF
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type
        ];
    }
}
