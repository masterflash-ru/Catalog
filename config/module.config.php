<?php
namespace Mf\Catalog;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;


return [
	//маршруты
    'router' => [
        'routes' => [
            'admin_tovar' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/adm/admin_tovar/:action',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
	 
    'service_manager' => [
        'factories' => [//сервисы-фабрики
            Service\Admin::class => Service\Factory\AdminFactory::class,
        ],
    ],

    //контроллеры
    'controllers' => [
        'factories' => [
            Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,
        ],
    	
	],
    
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];
