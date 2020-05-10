<?php
namespace Mf\Catalog\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;


/**
 * фабрика для вывода цены товара
 * 
 */
class TovarCurrency implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $cache = $container->get('DefaultSystemCache');
        $connection=$container->get('DefaultSystemDb');
        return new $requestedName($cache,$connection);
    }
}

