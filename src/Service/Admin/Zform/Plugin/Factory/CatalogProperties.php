<?php
namespace Mf\Catalog\Service\Admin\Zform\Plugin\Factory;

use Interop\Container\ContainerInterface;

/*

*/

class CatalogProperties
{

public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
{
	$connection=$container->get('DefaultSystemDb');
    return new $requestedName($connection);
}
}

