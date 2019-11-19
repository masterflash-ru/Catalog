<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Zend\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        //"description"=>"",
        "options" => [
            "container" => "catalog_tovar_gallery",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select id as img,catalog_tovar_gallery.* from catalog_tovar_gallery where catalog_tovar=:catalog_tovar",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["catalog_tovar_gallery","catalog_tovar_gallery"],
                    "keys"=>["catalog_tovar_gallery","catalog_tovar_gallery"],
                ],
                "db"=>[ 
                    "sql"=>"select * from catalog_tovar_gallery",
                    "PrimaryKey"=>"id",
                ],
            ],
            "add"=>[
                "db"=>[ 
                    "sql"=>"select * from catalog_tovar_gallery",
                    "PrimaryKey"=>"id",
                ],
                "cache" =>[
                    "tags"=>["catalog_tovar_gallery","catalog_tovar_gallery"],
                    "keys"=>["catalog_tovar_gallery","catalog_tovar_gallery"],
                ],
            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["catalog_tovar_gallery","catalog_tovar_gallery"],
                    "keys"=>["catalog_tovar_gallery","catalog_tovar_gallery"],
                ],
                "db"=>[ 
                    "sql"=>"select * from catalog_tovar_gallery",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Фотогалерея товара",
                "height" => "auto",
                "width" => 600,
                "rowNum" => 10,
                "rowList" => [10,20],
                "sortname" => "poz",
                "sortorder" => "asc",
                "viewrecords" => true,
                "autoencode" => false,
                "hidegrid" => false,
                //"toppager" => true,
                
                /*дает доп строку в конце сетки, из данных туда можно ставить итоги какие-либо*/
                //"footerrow"=> true, 
                //"userDataOnFooter"=> true,
               
                // "multiselect" => true,
                //"onSelectRow"=> new Expr("editRow"), //клик на строке вызов строчного редактора
        
                
                "rownumbers" => false,
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false]),
                    "editOptions"=>NavGridHelper::editOptions(["closeAfterEdit"=>false]),
                    "addOptions"=>NavGridHelper::addOptions(),
                    "delOptions"=>NavGridHelper::delOptions(),
                ],
                "colModel" => [
                    ColModelHelper::text("poz",["label"=>"POZ","width"=>90]),
                    ColModelHelper::image("img",
                                          ["label"=>"Фото",
                                           "plugins"=>[
                                               "read"=>[
                                                   "images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name" => "catalog_tovar_gallery",
                                                       "storage_item_rule_name"=>"imgb"   //имя правила из хранилища
                                                   ],
                                               ],
                                               "edit"=>[
                                                   "images" =>[
                                                       "storage_item_name" => "catalog_tovar_gallery",
                                                       "image_id"=>"id",                        //имя поля с ID
                                                   ],
                                               ],
                                               "del"=>[
                                                   "images" =>[
                                                       "storage_item_name" => "catalog_tovar_gallery",
                                                       "image_id"=>"id",                        //имя поля с ID
                                                   ],
                                               ],
                                               "add"=>[
                                                   "images" =>[
                                                       "storage_item_name" => "catalog_tovar_gallery",
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "database_table_name"=>"catalog_tovar_gallery"
                                                   ],
                                               ],
                                           ],
                                          ]),
                ColModelHelper::cellActions(),
                    
                
                ],
            ],
        ],
];