<?php
namespace Mf\Catalog\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Mf\Catalog\Service\Admin;

class AdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adminService=$container->get(Admin::class);
		return new $requestedName($adminService,$container);
    }
}