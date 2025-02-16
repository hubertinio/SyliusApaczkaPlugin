<?php

namespace Hubertinio\SyliusApaczkaPlugin\Model\Order;

class Address
{
    public function __construct(
        public string $countryCode,
        public string $name,
        public string $line1,
        public string $postalCode,
        public string $city,
        public string $contactPerson,
        public string $email,
        public string $phone,
        public string $foreignAddressId,
        public string $line2 = '',
        public bool $isResidential = false,
    ) {}

    public function toArray(): array
    {
        return [
            'country_code' => $this->countryCode,
            'name' => $this->name,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'postal_code' => $this->postalCode,
            'city' => $this->city,
            'is_residential' => (int) $this->isResidential, // Konwersja na int
            'contact_person' => $this->contactPerson,
            'email' => $this->email,
            'phone' => $this->phone,
            'foreign_address_id' => $this->foreignAddressId
        ];
    }
}
