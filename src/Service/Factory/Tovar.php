<?php

namespace Mf\Catalog\Service\Factory;

use Interop\Container\ContainerInterface;

use  Mf\Catalog\Service\Filter;
use  Mf\Catalog\Service\Price;

class Tovar
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $connection=$container->get('DefaultSystemDb');
        $cache=$container->get('DefaultSystemCache');
        $filter=$container->get(Filter::class);
        $price=$container->get(Price::class);
        return new $requestedName($connection,$cache,$filter,$price);
    }
}