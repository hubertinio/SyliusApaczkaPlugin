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

### Commands

```
bin/console sylius:shipping:apaczka:dev | jq '.response.services[] | .name,.service_id'
```

