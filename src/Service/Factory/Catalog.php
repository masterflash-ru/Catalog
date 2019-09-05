<?php

namespace Mf\Catalog\Service\Factory;

use Interop\Container\ContainerInterface;



class Catalog
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $connection=$container->get('DefaultSystemDb');
        $cache=$container->get('DefaultSystemCache');
        
        return new $requestedName($connection,$cache);
    }
}