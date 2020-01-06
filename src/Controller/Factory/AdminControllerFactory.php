<?php
namespace Mf\Catalog\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Mf\Storage\Service\ImagesLib;


class AdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $connection=$container->get('DefaultSystemDb');
        $ImagesLib=$container->get(ImagesLib::class);
        return new $requestedName($connection,$ImagesLib);
    }
}