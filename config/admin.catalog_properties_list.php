<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Admin\Service\Zform\RowModelHelper;
use Laminas\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        //"description"=>"Общие параметры товара",
        "options" => [
            "container" => "catalog_properties_list",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties_list where catalog_properties=:catalog_properties",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["catalog_properties_list"],
                    "keys"=>["catalog_properties_list"],
                ],

                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties_list",
                    "PrimaryKey"=>"id",
                ],

            ],
            "add"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties_list",
                    "PrimaryKey"=>"id",
                ],
            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["catalog_properties_list"],
                    "keys"=>["catalog_properties_list"],
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties_list",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Общие параметры - списки",
                "height" => "auto",
                //"width" => "1100px",
                "rowNum" => 20,
                "rowList" => [20,50,100],
                "sortname" => "value",
                "sortorder" => "asc",
                "viewrecords" => true,
                "autoencode" => false,
                //"autowidth"=>true,
                "hidegrid" => false,
                "toppager" => true,
                "rownumbers" => false,
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false,"edit"=>false,"del"=>false]),
                ],
                
                "colModel" => [
                    ColModelHelper::text("value",["label"=>"Значение параметра","width"=>300,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::text("xml_id",["label"=>"XML_ID","width"=>180,"editoptions" => ["size"=>50 ]]),
                    
                    ColModelHelper::cellActions(),
                ],
            ],
        ],
];