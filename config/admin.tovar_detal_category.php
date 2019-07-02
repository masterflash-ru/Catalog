<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;

use Zend\Validator;
use Zend\Filter;

return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal_category",
            "podval" =>"",
            "container-attr"=>"style='width:800px'",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select group_concat(catalog_category) as category from catalog_category2tovar where catalog_tovar=:id",  
                ],
            ],
            "edit"=>[
                Service\Admin\JqGrid\Plugin\SaveCategory2tovar::class=>[//запись внутри все есть
                ],
            ],
            
            /*поведение формы*/
            "actionsEvent"=>[
                /*что делать после успешной записи формы*/
                //"FormAfterSubmitOkEvent"=>'$("#catalog_tovar").trigger("reloadGrid");',
            ],

            /*внешний вид*/
            "layout"=>[
                "caption" => "Категории товара",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::select("category",[
                            'attributes'=>['multiple' => true,"size"=>25],
                            'options'=>[
                                "label"=>"Категории товара:"
                            ],
                            "plugins"=>[
                                "rowModel"=>[//плагин срабатывает при генерации формы до ее вывода
                                    Service\Admin\JqGrid\Plugin\GetCategoryTree::class=>[],
                                ],
                                "read"=>[//конвертирует строку со списком ID групп в массив
                                    "StringToArray"=>[]
                                ],
                            ],

                        ]),
                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                    ],
                ],
            ],
        ],
];