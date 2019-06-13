<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Zend\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Категории товара",
        "options" => [
            "container" => "catalog_category",
            "podval" =>"",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "TreeAdjacency"=>[//плагин выборки из базы
                    "sql"=>"select t.*,
                        (not EXISTS(select id from catalog_category as st where st.subid=t.id)) as isLeaf
                            from catalog_category as t where subid=:nodeid",
                    "interface_name"=>"catalog_category",
                ],
            ],
             "edit"=>[
                "TreeAdjacency"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_category",
                    "interface_name"=>"catalog_category",
                ],
                "cache" =>[
                    "tags"=>["catalog_category"],
                    "keys"=>["catalog_category"],
                ],
             ],
             "add"=>[
                "TreeAdjacency"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_category",
                    "parent_id_field" => "subid",
                    "interface_name"=>"catalog_category",
                ],
                "cache" =>[
                    "tags"=>["catalog_category"],
                    "keys"=>["catalog_category"],
                ],
             ],
             "del"=>[
                "TreeAdjacency"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_category",
                    "interface_name"=>"catalog_category",
                ],
                "cache" =>[
                    "tags"=>["catalog_category"],
                    "keys"=>["catalog_category"],
                ],
             ],
            
            /*события, создаются в виде 
            $("#<?=$options["container"]?>").bind("jqGridAddEditAfterSubmit", function () {  });
            */
            "bind"=>[
              //"jqGridAddEditAfterSubmit"=>new Expr("function () {print_catalog_category()}"),
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Категории товара",
                "height" => "auto",
                //"width" => "auto",
                "sortname" => "id",
                "sortorder" => "asc",
                "hidegrid" => false,
                "treeGrid"=>true,
                "ExpandColumn"=>"name",
                "ExpandColClick"=>true,
               "treeGridModel"=>"adjacency",
                "gridview"=>false,
                "treeIcons"=>[
                    "plus"  =>"ui-icon-triangle-1-e",
                    "minus"=>"ui-icon-triangle-1-s",
                    "leaf"=>"ui-icon-bullet",
                ],
                "treeReader"  =>[
                    "parent_id_field" => "subid",
                    "level_field" => "level",
                ], 
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false,"add"=>true,"edit"=>true,"del"=>true,"refresh"=>true]),
                    "editOptions"=>NavGridHelper::editOptions(["reloadAfterSubmit"=>true,]),
                    "addOptions"=>NavGridHelper::addOptions(["reloadAfterSubmit"=>true,"closeAfterAdd"=>true]),
                    "delOptions"=>NavGridHelper::delOptions(),
                ],
                "colModel" => [
                    ColModelHelper::text("name",
                                         [
                                             "label"=>"Имя",
                                             "width"=>250,
                                             "editoptions" => [
                                                 "size" => 80,
                                             ],
                                         ]),
                    ColModelHelper::text("url",["label"=>"URL категории",
                                                  "width"=>"500",
                                                 "editoptions" => [
                                                     "size" => 80,
                                                 ],
                                                "plugins"=>[
                                                    "edit"=>[
                                                        Service\Admin\JqGrid\Plugin\CatalogTranslit::class=>[],
                                                    ],
                                                    "edit"=>[
                                                        Service\Admin\JqGrid\Plugin\CatalogTranslit::class=>[],
                                                    ],
                                                    "add"=>[
                                                        Service\Admin\JqGrid\Plugin\CatalogTranslit::class=>[],
                                                    ],
                                                ],
                                                  ]),
                    ColModelHelper::cellActions("myaction",["formatoptions"=>["editformbutton"=>true]]),
                    ColModelHelper::hidden("id"),
                ],
            ],
        ],
];