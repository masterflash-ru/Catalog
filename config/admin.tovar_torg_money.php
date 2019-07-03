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
                "db1"=>[//если записи нет, тогда вставим пустую
                    "sql"=>"insert into catalog_tovar_currency (catalog_tovar)
                       select id from catalog_tovar where id=:id 
                        and NOT EXISTS (select catalog_tovar from catalog_tovar_currency where catalog_tovar=:id)",
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar_currency where catalog_tovar=:id",
                ],
            ],
            "edit"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar_currency ", 
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
                        RowModelHelper::select("catalog_currency",[
                            'options'=>[
                                "label"=>"Валюта базовой цены:"
                            ],
                            "plugins"=>[
                                "rowModel"=>[//плагин срабатывает при генерации формы до ее вывода
                                    Plugin\SelectFromDb::class=>[
                                        "sql"=>"select currency as id, currency as name from catalog_currency order by poz"
                                    ],
                                ],
                            ],

                        ]),

                        RowModelHelper::text("value",['options'=>["label"=>"Базовая цена"]]),
                        RowModelHelper::checkbox("nds",['options'=>["label"=>"НДС включен в цену"]]),
                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                        //это ID товара
                        RowModelHelper::hidden("catalog_tovar"),
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