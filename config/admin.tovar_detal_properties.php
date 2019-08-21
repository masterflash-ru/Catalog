<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;

use Zend\Validator;
use Zend\Filter;

return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal_properties",
            "podval" =>"",
            "container-attr"=>"style='width:800px'",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                Service\Admin\Zform\Plugin\CatalogProperties::class=>[],
            ],
            "edit"=>[
                Service\Admin\Zform\Plugin\CatalogProperties::class=>[],
            ],
            
            /*поведение формы*/
            "actionsEvent"=>[
                /*что делать после успешной записи формы*/
                //"FormAfterSubmitOkEvent"=>'$("#catalog_tovar").trigger("reloadGrid");', 
            ],

            /*внешний вид*/
            "layout"=>[
                "caption" => "Свойства товара",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::DynamicArray(null,[
                            'fields'=>[
                               // RowModelHelper::text("xml_id",['options'=>["label"=>"xml_id"]]),
                               // RowModelHelper::text("xml_id111",['options'=>["label"=>"xml_id111"]]),
                            ],
                            "plugins"=>[
                                "read"=>[
                                    Service\Admin\Zform\Plugin\CatalogProperties::class=>[]
                                ]
                            ]
                        ]),

                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                        //это ID товара
                        //RowModelHelper::hidden("id"),
                    ],
                    /*конфигурация фильтров и валидаторов*/
                    'input_filter' => [
                    ],//input_filter
                ],
            ],
        ],
];