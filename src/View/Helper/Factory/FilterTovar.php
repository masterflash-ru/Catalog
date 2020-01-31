<?php
namespace Mf\Catalog\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * фабрика для фильтра товара в каталоге
 * 
 */
class FilterTovar implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $cache = $container->get('DefaultSystemCache');
        $connection=$container->get('DefaultSystemDb');
        return new $requestedName($connection,$cache);
    }
}

