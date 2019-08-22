<?php
namespace Mf\Catalog\Service\Admin\JqGrid\Plugin\Factory;

use Interop\Container\ContainerInterface;

/*

*/

class CatalogTovarStore
{

public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
{
	$connection=$container->get('DefaultSystemDb');
    return new $requestedName($connection);
}
}

