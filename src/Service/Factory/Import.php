<?php
namespace Mf\Catalog\Service\Factory;

use Interop\Container\ContainerInterface;


class Import
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $connection = $container->get('DefaultSystemDb');
        return new $requestedName($connection);
    }
}

