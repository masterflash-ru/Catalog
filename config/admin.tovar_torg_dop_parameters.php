<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;
use Admin\Service\Zform\Plugin;

return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_torg_gabarits",
            "podval" =>"",
            "container-attr"=>"style='width:500px'",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                 Service\Admin\Zform\Plugin\CatalogDopProperties::class=>[],
            ],
            "edit"=>[
                Service\Admin\Zform\Plugin\CatalogDopProperties::class=>[],
            ],
            
            /*поведение формы*/
            "actionsEvent"=>[
                /*что делать после успешной записи формы*/
            ],

            /*внешний вид*/
            "layout"=>[
                "caption" => "Дополнительные параметры товара",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::select("catalog_measure_code",[
                            'options'=>[
                                "label"=>"Единица измерения:"
                            ],
                            "plugins"=>[
                                "rowModel"=>[//плагин срабатывает при генерации формы до ее вывода
                                    Plugin\SelectFromDb::class=>[
                                        "sql"=>"select code as id, measure_title as name from catalog_measure order by is_default desc, name asc"
                                    ],
                                ],
                            ],

                        ]),
                        RowModelHelper::text("coefficient",['options'=>["label"=>"Коэффициент единицы измерения"]]),
                        RowModelHelper::text("quantity",['options'=>["label"=>"Общее количество товара"]]),
                        RowModelHelper::text("weight",['options'=>["label"=>"Вес (грамм)"]]),
                        RowModelHelper::text("length",['options'=>["label"=>"Длина (мм)"]]),
                        RowModelHelper::text("width",['options'=>["label"=>"Ширина (мм)"]]),
                        RowModelHelper::text("height",['options'=>["label"=>"Высота (мм)"]]),

                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                        //это ID товара
                        RowModelHelper::hidden("catalog_tovar"),
                    ],

                ],
            ],
        ],
];