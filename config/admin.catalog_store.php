<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Admin\Service\Zform\RowModelHelper;
use Zend\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Склады",
        "options" => [
            "container" => "catalog_store",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_store",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["catalog_store"],
                    "keys"=>["catalog_store"],
                ],

                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_store",
                    "PrimaryKey"=>"id",
                ],

            ],
            "add"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_store",
                    "PrimaryKey"=>"id",
                ],
            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["catalog_store"],
                    "keys"=>["catalog_store"],
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_store",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Склады",
                "height" => "auto",
                //"width" => "1100px",
                "rowNum" => 20,
                "rowList" => [20,50],
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
                    //"editOptions"=>NavGridHelper::editOptions(),
                   // "addOptions"=>NavGridHelper::addOptions(),
                    //"delOptions"=>NavGridHelper::delOptions(),
                ],
                
                "colModel" => [
                    ColModelHelper::text("id",["label"=>"ID","width"=>80,"editable" => false]),
                    ColModelHelper::text("name",["label"=>"Название склада","width"=>300,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::textarea("address",["label"=>"Адрес","width"=>250,"editoptions" => ["cols"=>50 ]]),
                    ColModelHelper::textarea("description",["label"=>"Описание","width"=>300,"editoptions" => ["cols"=>50 ],"hidden"=>true,"editrules"=>["edithidden"=>true]]),
                    ColModelHelper::checkbox("public",["label"=>"Публ.","width"=>50]),
                    ColModelHelper::text("xml_id",["label"=>"XML_ID","width"=>180,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::text("poz",["label"=>"сорт","width"=>80]),
                ],
            ],
        ],
];