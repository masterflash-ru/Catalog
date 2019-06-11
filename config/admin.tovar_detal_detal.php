<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;



return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal_detal",
            "podval" =>"",
            "container-attr"=>"style='width:800px'",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar where id=:id",  
                ],
            ],
            "edit"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar",  
                ],
            ],
            
            /*поведение формы*/
            "actionsEvent"=>[
                /*что делать после успешной записи формы*/
                //"FormAfterSubmitOkEvent"=>'$("#catalog_tovar").trigger("reloadGrid");',
            ],

            /*внешний вид*/
            "layout"=>[
                "caption" => "Подробное описание товара (карточка)",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::ckeditor("info",['options'=>["label"=>"Подробное описание"]]),
                        //RowModelHelper::image("img",['options'=>["label"=>"Фото товара"]]),


                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                        //это ID товара
                        RowModelHelper::hidden("id"),
                    ],

                ],
            ],
        ],
];