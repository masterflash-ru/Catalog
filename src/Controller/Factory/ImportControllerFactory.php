<?php
namespace Mf\Catalog\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Mf\Catalog\Service\Import;


class ImportControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //$connection=$container->get('DefaultSystemDb');
        $import=$container->get(Import::class);
        return new $requestedName($import);
    }
}