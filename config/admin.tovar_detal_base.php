<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;


return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal_base",
            "podval" =>"",
            "container-attr"=>"style='width:500px'",
        
            
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
                "FormAfterSubmitOkEvent"=>'$("#catalog_tovar").trigger("reloadGrid");',
            ],

            /*внешний вид*/
            "layout"=>[
                "caption" => "Основная информация товара",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::text("name",['options'=>["label"=>"Наименование"]]),
                        RowModelHelper::text("url",[
                            'options'=>[
                                "label"=>"URL карточки"
                            ],
                            "plugins"=>[
                                "edit"=>[
                                    "Translit"=>[
                                        "source"=>"name",
                                    ],
                                ],
                                "add"=>[
                                    "Translit"=>[
                                        "source"=>"name",
                                    ],
                                ],
                            ],
                        ]),
                        RowModelHelper::text("poz",['options'=>["label"=>"Сортировка"]]),
                        RowModelHelper::checkbox("public",['options'=>["label"=>"Публиковать"]]),
                        RowModelHelper::text("xml_id",['options'=>["label"=>"xml_id"]]),
                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                        //это ID товара
                        RowModelHelper::hidden("id"),
                    ],
                    'input_filter' => [
                        "name" => [
                            'required' => true,
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