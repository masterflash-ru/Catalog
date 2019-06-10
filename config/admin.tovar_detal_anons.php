<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;


return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal_anons",
            "podval" =>"",
            "container-attr"=>"style='width:800px'",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select catalog_tovar.*, id as img from catalog_tovar where id=:id",  
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
                "caption" => "Анонс товара",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::uploadimage("img",
                                              [
                                                  'options'=>["label"=>"Фото товара"],
                                                  /*"plugins"=>[
                                                      "read"=>[
                                                          "Images" =>[
                                                              "storage_item_name" => "catalog_tovar_anons", //имя секции в хранилище
                                                              "storage_item_rule_name"=>"admin_img"         //имя правила из хранилища
                                                          ],
                                                      ],
                                                  ],*/
                                              ]),
                        //RowModelHelper::file("id",['options'=>["label"=>"Новое фото товара"]]),

                        //RowModelHelper::ckeditor("anons",['options'=>["label"=>"Анонс"]]),

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