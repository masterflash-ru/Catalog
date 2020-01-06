<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Laminas\Json\Expr;



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
                    "sql"=>"select t.*,id as img,
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
                    "editOptions"=>NavGridHelper::editOptions(["reloadAfterSubmit"=>true,"closeAfterEdit"=>true]),
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
                    ColModelHelper::text("poz",
                                         [
                                             "label"=>"Порядок",
                                             "width"=>80,
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
                        ColModelHelper::image("img",
                                              [
                                                  'options'=>["label"=>"Фото узла"],
                                                  "plugins"=>[
                                                      "read"=>[
                                                          "Images" =>[
                                                              "storage_item_name" => "catalog_category", //имя секции в хранилище
                                                              "storage_item_rule_name"=>"img"         //имя правила из хранилища
                                                          ],
                                                      ],
                                                      "edit"=>[
                                                          "Images"=>[
                                                              "storage_item_name" => "catalog_category",              //имя секции в хранилище
                                                              "image_id"=>"id"
                                                          ],
                                                      ],
                                                       "add"=>[
                                                           "Images" =>[
                                                               "storage_item_name" => "catalog_category",
                                                               "database_table_name"=>"catalog_category",
                                                               "image_id"=>"id"
                                                           ],
                                                       ],

                                                  ],
                                              ]),

                    ColModelHelper::ckeditor("info",[
                        "label"=>"Подробно категория",
                        "plugins"=>[
                            "edit"=>[
                                "ClearContent"=>[],
                            ],
                            "add"=>[
                                "ClearContent"=>[],
                            ],
                        ],
                    ]),
                    
                    ColModelHelper::textarea("title",["label"=>"TITLE","hidden"=>true,"editrules"=>["edithidden"=>true]]),
                    ColModelHelper::textarea("keywords",["label"=>"KEYWORDS","hidden"=>true,"editrules"=>["edithidden"=>true]]),
                    ColModelHelper::textarea("description",["label"=>"DESCRIPTION","hidden"=>true,"editrules"=>["edithidden"=>true]]),

                    ColModelHelper::cellActions(),
                    ColModelHelper::hidden("id"),
                ],
            ],
        ],
];