<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;
use Admin\Service\Zform\Plugin;

return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_torg_money",
            "podval" =>"",
            "container-attr"=>"style='width:500px'",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                Service\Admin\Zform\Plugin\CatalogPriceType::class=>[],
            ],
            "edit"=>[
                Service\Admin\Zform\Plugin\CatalogPriceType::class=>[],
                "cache" =>[
                    "tags"=>["catalog_price_type","catalog_tovar_currency"],
                    "keys"=>["catalog_price_type","catalog_tovar_currency"],
                ],

            ],
            
            /*поведение формы*/
            "actionsEvent"=>[
                /*что делать после успешной записи формы*/
            ],

            /*внешний вид*/
            "layout"=>[
                "caption" => "Управление ценами",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::DynamicArray(null,[
                            'fields'=>[
                               // RowModelHelper::text("xml_id",['options'=>["label"=>"xml_id"]]),
                               // RowModelHelper::text("xml_id111",['options'=>["label"=>"xml_id111"]]),
                            ],
                            "plugins"=>[
                                "read"=>[
                                    Service\Admin\Zform\Plugin\CatalogPriceType::class=>[]
                                ]
                            ]
                        ]),
                        
                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                        //это ID товара
                       // RowModelHelper::hidden("catalog_tovar"),
                        RowModelHelper::hidden("id"),
                    ],
                    'input_filter' => [
                        "value" => [
                            'required' => false,
                            'filters' => [
                                [ 'name' => 'StringTrim' ],
                                [ 'name' => 'StripTags' ],
                            ],
                        ],
                    ],

                ],
            ],
        ],
];