<?php

namespace Mf\Catalog\Service\Factory;

use Interop\Container\ContainerInterface;

use  Mf\Catalog\Service\Price;

class Filter
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $connection=$container->get('DefaultSystemDb');
        $cache=$container->get('DefaultSystemCache');
        $price=$container->get(Price::class);
        return new $requestedName($connection,$cache,$price);
    }
}