<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Sylius Apaczka.pl Plugin</h1>

<p align="center">
   <a href="https://www.apaczka.pl/">apaczka.pl</a>
</p>

### TODO

- osadzenie mapy https://panel.apaczka.pl/dokumentacja_api_v2_mapa.php
- push tracking https://www.apaczka.pl/app/uploads/2023/06/Push-tracking-API-v2-2.pdf

### Installation

Add this line to config/packages/_sylius.yml

```
- { resource: "@HubertinioSyliusApaczkaPlugin/config/config.yml" }
```

Add this line to config/routes/hubertinio_sylius_apaczka.yaml

```
hubertinio_sylius_apaczka_shop:
    resource: "@HubertinioSyliusApaczkaPlugin/config/shop_routing.php"
    prefix: /{_locale}
    requirements:
        _locale: ^[a-z]{2}(?:_[A-Z]{2})?$

hubertinio_sylius_apaczka_admin:
    resource: "@HubertinioSyliusApaczkaPlugin/config/admin_routing.php"
    prefix: /admin

```

```

### Commands

```
bin/console sylius:shipping:apaczka:dev | jq '.response.services[] | .name,.service_id'
```

