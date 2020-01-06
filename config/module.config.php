<?php
namespace Mf\Catalog;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

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

use Laminas\Validator\File\IsImage;
use Laminas\Validator\File\ImageSize;

use Mf\Navigation;

return [
	//маршруты
    'router' => [
        'routes' => [
            'catalog' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/catalog',
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'catalog_tovar' => [
                'type' => Segment::class,
                    'options' => [
                        'route'    => '/catalog/:catalog/:url',
                      'constraints' => [
                            'catalog' => '[a-zA-Z0-9_\-]+',
                          'url' => '[a-zA-Z0-9_\-]+',
                      ],
                        'defaults' => [
                            'controller' => Controller\CatalogController::class,
                            'action'     => 'tovar',
                        ],
                    ],
             ],
            'catalog_list' => [
                'type' => Segment::class,
                    'options' => [
                        'route'    => '/catalog/:catalog[/page/:page]',
                      'constraints' => [
                        'url' => '[a-zA-Z0-9_\-]+',
                          'catalog' => '[a-zA-Z0-9_\-]+',
                          'page' => '\d+',
                      ],
                        'defaults' => [
                            'controller' => Controller\CatalogController::class,
                            'action'     => 'list',
                            "page"=>0
                        ],
                    ],
             ],
            'admin_catalog_new' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/adm/admin_catalog/tovar_new',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'tovarnew',
                    ],
                ],
            ],
            'import_file_1c_cron' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/1c/cron',
                    'defaults' => [
                        'controller' => Controller\ImportController::class,
                        'action'     => 'cron',
                    ],
                ],
            ],
        ],
    ],
	 
    'service_manager' => [
        'factories' => [//сервисы-фабрики
            Service\Import::class => Service\Factory\Import::class,
            Service\Catalog::class => Service\Factory\Catalog::class,
        ],
    ],

    //контроллеры
    'controllers' => [
        'factories' => [
            Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,
            Controller\ImportController::class => Controller\Factory\ImportControllerFactory::class,
            Controller\CatalogController::class => Controller\Factory\CatalogControllerFactory::class,
        ],
	],
    'view_helpers' => [
        'factories' => [
            View\Helper\MenuCategory::class => Navigation\View\Helper\Factory\HelperFactory::class,
        ],
        'aliases' => [
            'MenuCategory' =>View\Helper\MenuCategory::class
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
        "catalog_store"=>__DIR__."/admin.catalog_store.php",                //склады
        "catalog_properties"=>__DIR__."/admin.catalog_properties.php",      //общие параметры товара
        "catalog_properties_list"=>__DIR__."/admin.catalog_properties_list.php",      //общие параметры списки
        "catalog_price_type" => __DIR__."/admin.catalog_price_type.php",     //типы цен
        
        "catalog_tovar_gallery" => __DIR__."/admin.tovar_gallery.php",     //фотогалерея товара
        "tovar_catalog_gallery"=>__DIR__."/admin.tovar_catalog_gallery.php", //сама галерея
        "catalog_tovar"=>__DIR__."/admin.catalog_tovar.php",                //список всех товаров
        "tovar_detal"=>__DIR__."/admin.tovar_detal.php",                    //табы для редактирвоания товара
        "tovar_detal_base"=>__DIR__."/admin.tovar_detal_base.php",          //базовая информация
        "tovar_detal_properties"=>__DIR__."/admin.tovar_detal_properties.php", //общие св-ва товара
        "tovar_detal_anons"=>__DIR__."/admin.tovar_detal_anons.php",        //подробно товар - анонс
        "tovar_detal_detal"=>__DIR__."/admin.tovar_detal_detal.php",        //подробно товар - подр. описание
        "tovar_catalog_category"=>__DIR__."/admin.catalog_category.php",    //категории товара
        "tovar_detal_category"=>__DIR__."/admin.tovar_detal_category.php",  //подробно товар - категории
        "tovar_torg_catalog"=>__DIR__."/admin.tovar_torg_catalog.php",      //торговый каталог (табы)
        "tovar_torg_money"=>__DIR__."/admin.tovar_torg_money.php",          //цена  в торговом каталоге
        "tovar_torg_store"=>__DIR__."/admin.tovar_torg_store.php",          //остатки на складах
        "tovar_torg_dop_parameters"=>__DIR__."/admin.tovar_torg_dop_parameters.php",    //доп. параметры товара
        
    ],
    /*плагины для сетки JqGrid*/
    "JqGridPlugin"=>[
        'factories' => [
            Service\Admin\JqGrid\Plugin\CatalogTranslit::class => Service\Admin\JqGrid\Plugin\Factory\CatalogTranslit::class,
            Service\Admin\JqGrid\Plugin\CatalogTovarStore::class => Service\Admin\JqGrid\Plugin\Factory\CatalogTovarStore::class,
        ],
        'aliases' =>[
        ],
    ],
    /*плагины для Zform*/
    "ZformPlugin"=>[
        'factories' => [
            Service\Admin\JqGrid\Plugin\GetCategoryTree::class => Service\Admin\JqGrid\Plugin\Factory\GetCategoryTree::class,
            Service\Admin\JqGrid\Plugin\SaveCategory2tovar::class => Service\Admin\JqGrid\Plugin\Factory\SaveCategory2tovar::class,
            Service\Admin\Zform\Plugin\CatalogProperties::class => Service\Admin\Zform\Plugin\Factory\CatalogProperties::class,
            Service\Admin\Zform\Plugin\CatalogDopProperties::class => Service\Admin\Zform\Plugin\Factory\CatalogDopProperties::class,
            Service\Admin\Zform\Plugin\CatalogPriceType::class => Service\Admin\Zform\Plugin\Factory\CatalogPriceType::class,
        ],
        'aliases' =>[
        ],
    ],
    
    "catalog"=>[
        "config"=>[
            "database"  =>  "DefaultSystemDb",
            "cache"     =>  "DefaultSystemCache",
        ],
        "vat_values"=>[
            /*ставки НДС в %*/
            20 => "20 %",
            10 => "10 %",
        ],
        "default"=>[
            "vat_in" => true,           /*НДС включен в цену товара (по умолчанию, влияет на "крыжик при создании товара")*/
            "vat_value" => 20,          /*ставка НДС по умолчанию*/
            "currency"=>"RUB",          /*Имя валюты по умолчанию*/
        ],
        "import" =>[
            "1c" => [                                           //опции импорта из 1С
                "service_import"=> Service\Import::class,       //обработчик импорта из 1С, если он не нужен, укажите false
                "service_requisites"=>Service\Import::class,    //обработчик доп.реквизитов товара - это очень индивидуально, сильно зависит от настроек 1С
            ],
            "limit_record_read_attach_files"=>220,              //предел кол-ва обрабатываемых записей с файлами за 1 раз
            //очищать общие параметры товара при полной перезагрузке каталога в виде SQL или false,true или строка к SQL (например, xml_id>'' and (sysname is null or sysname=''))
            "clear_catalog_properties_sql_full_reload"=>"xml_id>'' and (sysname is null or sysname='')",  
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
                            'img'=>[
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
                            'img'=>[
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
            "catalog_tovar_gallery"=>[
                "description"=>"Фотогалерея товара",
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
                            'img'=>[
                                'filters'=>[
                                        CopyToStorage::class => [
                                                    'folder_level'=>0,
                                                    'folder_name_size'=>3,
                                                    'strategy_new_name'=>'md5'
                                        ],
                                ],
                            ],
                ],
            ],//catalog_tovar_gallery
        ],
    ],

];
