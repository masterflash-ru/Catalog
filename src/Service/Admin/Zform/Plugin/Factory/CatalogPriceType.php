<?php
namespace Mf\Catalog\Service\Admin\Zform\Plugin\Factory;

use Interop\Container\ContainerInterface;

/*

*/

class CatalogPriceType
{

public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
{
	$connection=$container->get('DefaultSystemDb');
    $config=$container->get('config');
    return new $requestedName($connection,$config);
}
}

