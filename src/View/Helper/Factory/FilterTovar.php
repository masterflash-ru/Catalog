<?php
namespace Mf\Catalog\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use  Mf\Catalog\Service\Filter;

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
        $filter=$container->get(Filter::class);
        return new $requestedName($filter,$cache,$connection);
    }
}

