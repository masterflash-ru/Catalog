<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Admin\Service\Zform\RowModelHelper;
use Zend\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Управление товаром на складах",
        "options" => [
            "container" => "catalog_tovar_store",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"
                        select id as catalog_store,name,address,:id as tovar_id,
                            (select quantity from catalog_tovar_store where catalog_store=catalog_store.id and catalog_tovar=:id) as quantity,
                            (select id from catalog_tovar_store where catalog_store=catalog_store.id and catalog_tovar=:id) as quantity_id,
                            (select xml_id from catalog_tovar_store where catalog_store=catalog_store.id and catalog_tovar=:id) as xml_id
                                from catalog_store",
                    "PrimaryKey"=>"catalog_store",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["catalog_tovar_store"],
                    "keys"=>["catalog_tovar_store"],
                ],
                Service\Admin\JqGrid\Plugin\CatalogTovarStore::class=>[]
            ],

            
            /*внешний вид*/
            "layout"=>[
                "caption" => "Общие параметры товара",
                "height" => "auto",
                //"width" => "1100px",
                "rowNum" => 20,
                //"rowList" => [20,50,100],
               // "sortname" => "id",
               // "sortorder" => "asc",
               // "viewrecords" => true,
                "autoencode" => false,
                //"autowidth"=>true,
                "hidegrid" => false,
                //"toppager" => true,
                "rownumbers" => false,
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false,"add"=>false,"del"=>false,"edit"=>false]),
                ],
                
                "colModel" => [
                    ColModelHelper::text("tovar_id",["label"=>"ID товара","width"=>80,"editoptions" =>["disabled"=>"disabled"]]),
                    
                    ColModelHelper::text("catalog_store",["label"=>"ID скалада","width"=>80,"editoptions" =>["disabled"=>"disabled"]]),
                    ColModelHelper::text("quantity_id",["label"=>"ID зап.","width"=>80,"editoptions" =>["disabled"=>"disabled"]]),
                    ColModelHelper::text("name",["label"=>"Имя склада","editable" => false]),
                    ColModelHelper::text("address",["label"=>"Адрес склада","editable" => false]),
                    ColModelHelper::text("quantity",["label"=>"Остаток товара","width"=>180,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::text("xml_id",["label"=>"XML_ID","width"=>180,"editoptions" => ["size"=>50 ]]),
                    
                    
                    
                    ColModelHelper::cellActions(),
                ],
            ],
        ],
];