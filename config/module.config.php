<?php
namespace Mf\Catalog;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

/*адаптеры ресайза*/
use Mf\Imglib\Filter\Adapter\Gd;
use Mf\Imglib\Filter\Adapter\Consoleimagick  as ImgAdapter;
use Mf\Imglib\Filter\Adapter\Imagick;

/** 
* адаптеры (фильтры) ресайза, оптимизации и наложения водных знаков на фото
* это обертка к выбранному адаптеру, см. выше
*/
use Mf\Imglib\Filter\ImgResize;
use Mf\Imglib\Filter\ImgOptimize;
use Mf\Imglib\Filter\Watermark;
/*
как обрабатывать фото определяют эти константы:
IMG_METHOD_SCALE_WH_CROP //точное вырезание
IMG_METHOD_SCALE_FIT_W   //точно по горизонатали, вертикаль пропорционально
IMG_METHOD_SCALE_FIT_H   //точно к вертикали, горизонталь пропорционально
IMG_METHOD_CROP"         //просто вырезать из исходного часть

IMG_ALIGN_CENTER         //выравнивать по центру
IMG_ALIGN_LEFT          //выравнивать по левой части
IMG_ALIGN_RIGHT         //выравнивать по правой
IMG_ALIGN_TOP            //выравнивать по верху
IMG_ALIGN_BOTTOM        //выравнивать по низу
*/
/**
* специальный фильтр для генерации альтернативных форматов изображений
*/
use Mf\Imglib\Filter\ImgAlternative;


/*фильтр копировщик файлов в хранилище*/
use Mf\Storage\Filter\CopyToStorage;

use Zend\Validator\File\IsImage;
use Zend\Validator\File\ImageSize;



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
    'view_helper_config' => [
        'asset' => [
            'resource_map' => [
                'backend'=>[
                    'js'=>[
                        100=>"js/admin/catalog.js?".date("d.Y"),
                    ],
                ],
            ],
        ],
    ],

    /*доступ к панели управления*/
    "permission"=>[
        "objects" =>[
            "interface/catalog_tovar" => [1,1,0760],
        ],
    ],

    /*сетка для админки*/
    "interface"=>[
        "catalog_tovar"=>__DIR__."/admin.catalog_tovar.php",        //список всех товаров
        "tovar_detal"=>__DIR__."/admin.tovar_detal.php",            //табы для редактирвоания товара
        "tovar_detal_base"=>__DIR__."/admin.tovar_detal_base.php",  //базовая информация
        "tovar_detal_anons"=>__DIR__."/admin.tovar_detal_anons.php",
        "tovar_detal_detal"=>__DIR__."/admin.tovar_detal_detal.php",
        "tovar_catalog_category"=>__DIR__."/admin.catalog_category.php",
        "tovar_detal_category"=>__DIR__."/admin.tovar_detal_category.php",
    ],
    /*плагины для сетки JqGrid*/
    "JqGridPlugin"=>[
        'factories' => [
            Service\Admin\JqGrid\Plugin\CatalogTranslit::class => Service\Admin\JqGrid\Plugin\Factory\CatalogTranslit::class,
        ],
        'aliases' =>[
        ],
    ],
    /*плагины для Zform*/
    "ZformPlugin"=>[
        'factories' => [
            Service\Admin\JqGrid\Plugin\GetCategoryTree::class => Service\Admin\JqGrid\Plugin\Factory\GetCategoryTree::class,
            Service\Admin\JqGrid\Plugin\SaveCategory2tovar::class => Service\Admin\JqGrid\Plugin\Factory\SaveCategory2tovar::class,
        ],
        'aliases' =>[
        ],
    ],

/*хранилище и обработка (ресайз) фото и других файлов*/
    "storage"=>[
        /* Эти настройки должгы быть в конфиге вашего приложения
        'data_folder'=>"data/datastorage",
        'file_storage'=>[
            'default'=>[
                'base_url'=>"media/pics/",
            ],
        ],*/

        //базовые настройки хранения фото товара
        //укажите одноименные настройки в вашем приложении для изменения
        'items'=>[
            "catalog_tovar_anons"=>[
                "description"=>"Фото товара в списке",
                'file_storage'=>'default',
                'file_rules'=>[
                            'admin_img'=>[
                                'filters'=>[
                                        CopyToStorage::class => [
                                                    'folder_level'=>0,
                                                    'folder_name_size'=>3,
                                                    'strategy_new_name'=>'md5'
                                        ],
                                ],
                            ],
                            'anons'=>[
                                'filters'=>[
                                        CopyToStorage::class => [
                                                    'folder_level'=>0,
                                                    'folder_name_size'=>3,
                                                    'strategy_new_name'=>'md5'
                                        ],
                                ],
                            ],
                ],
            ],//catalog_tovar_anons
            "catalog_tovar_detal"=>[
                "description"=>"Фото товара в карточке",
                'file_storage'=>'default',
                'file_rules'=>[
                            'admin_img'=>[
                                'filters'=>[
                                        CopyToStorage::class => [
                                                    'folder_level'=>0,
                                                    'folder_name_size'=>3,
                                                    'strategy_new_name'=>'md5'
                                        ],
                                ],
                            ],
                            'anons'=>[
                                'filters'=>[
                                        CopyToStorage::class => [
                                                    'folder_level'=>0,
                                                    'folder_name_size'=>3,
                                                    'strategy_new_name'=>'md5'
                                        ],
                                ],
                            ],
                ],
            ],//catalog_tovar_detal
        ],
    ],

];
