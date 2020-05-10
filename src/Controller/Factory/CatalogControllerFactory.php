<?php

namespace Mf\Catalog\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Form\View\Helper\FormElement;
use Mf\Catalog\Service\Catalog;
use Mf\Catalog\Service\Tovar;

class CatalogControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $catalog=$container->get(Catalog::class);
        $tovar=$container->get(Tovar::class);
        $cache=$container->get('DefaultSystemCache');
        
        return new $requestedName($catalog,$tovar,$cache);
    }
}