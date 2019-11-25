<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Admin\Service\Zform\RowModelHelper;
use Zend\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Типы цен",
        "options" => [
            "container" => "catalog_price_type",
            "caption" => "",
            "podval" => "<b>Обязательно один из типов должен быть отмечен о умолчанию!</b>",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_price_type",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["catalog_price_type"],
                    "keys"=>["catalog_price_type"],
                ],
                "db1"=>[//сбросим флаг is_base
                    "sql"=>"update catalog_price_type set is_base=null",
                    "NotCreateRecordSet"=>true,
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_price_type",
                    "PrimaryKey"=>"id",
                ],

            ],
            "add"=>[
                "db1"=>[//сбросим флаг is_base
                    "sql"=>"update catalog_price_type set is_base=null",
                    "NotCreateRecordSet"=>true,
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_price_type",
                    "PrimaryKey"=>"id",
                ],
                "cache" =>[
                    "tags"=>["catalog_price_type"],
                    "keys"=>["catalog_price_type"],
                ],
            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["catalog_price_type"],
                    "keys"=>["catalog_price_type"],
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_price_type",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Типы цен",
                "height" => "auto",
                //"width" => "1100px",
                "rowNum" => 20,
                "rowList" => [20,50,100],
                "sortname" => "name",
                "sortorder" => "asc",
                "viewrecords" => true,
                "autoencode" => false,
                //"autowidth"=>true,
                "hidegrid" => false,
                "toppager" => true,
                "rownumbers" => false,
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false]),
                ],
                
                "colModel" => [
                    ColModelHelper::text("id",["label"=>"ID","width"=>80,"editable" => false]),
                    ColModelHelper::text("name",["label"=>"Имя цены","width"=>250,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::checkbox("is_base",["label"=>"Базовая"]),
                    ColModelHelper::text("xml_id",["label"=>"XML_ID","width"=>180,"editoptions" => ["size"=>50 ]]),
                    
                    ColModelHelper::cellActions(),
                ],
            ],
        ],
];