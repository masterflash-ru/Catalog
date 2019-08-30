<?php
namespace Mf\Catalog\Service\Factory;

use Interop\Container\ContainerInterface;
use Mf\Storage\Service\ImagesLib;

class Import
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $connection = $container->get('DefaultSystemDb');
        $config=$container->get('config');
        $ImagesLib=$container->get(ImagesLib::class);
        return new $requestedName($connection,$ImagesLib,$config);
    }
}

