<?php

namespace Hubertinio\SyliusApaczkaPlugin\Model\Order;

class COD
{
    public function __construct(
        public int $amount = 0,
        public string $bankAccount = '',
    ) {}

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'bankaccount' => $this->bankAccount
        ];
    }
}
