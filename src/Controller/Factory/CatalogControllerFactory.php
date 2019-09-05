<?php

namespace Mf\Catalog\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\Form\View\Helper\FormElement;
use Mf\Catalog\Service\Catalog;


class CatalogControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $catalog=$container->get(Catalog::class);
        $cache=$container->get('DefaultSystemCache');
        
        return new $requestedName($catalog,$cache);
    }
}