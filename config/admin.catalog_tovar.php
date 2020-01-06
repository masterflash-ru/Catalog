<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Admin\Service\Zform\RowModelHelper;
use Laminas\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Список товара",
        "options" => [
            "container" => "catalog_tovar",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar where new_date is null or (new_date and new_session='".session_id()."')",
                    "PrimaryKey"=>"id",
                ],
            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["catalog_tovar"],
                    "keys"=>["catalog_tovar"],
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar",
                    "PrimaryKey"=>"id",
                ],
                "Images"=>[
                    "storage_items_array"=>[
                        "catalog_tovar_detal",
                        "catalog_tovar_anons",
                    ]
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Список всего товара",
                "height" => "auto",
                //"width" => "1100px",
                "rowNum" => 20,
                "rowList" => [20,50,100,500],
                "sortname" => "name",
                "sortorder" => "asc",
                "viewrecords" => true,
                "autoencode" => false,
                //"autowidth"=>true,
                "hidegrid" => false,
                "toppager" => true,
                "rownumbers" => false,
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false,"edit"=>false,"add"=>false,"del"=>false]),
                ],
                
                //"colMenu"  =>  true ,
                /*область перед телом сетки, toolbar
                * все настройки как в Zform
                */
                "toolbar"=> [true,"top"],
                "toolbarModel"=>[
                    "rowModel" => [
                        'elements' => [
                            RowModelHelper::button("new_Tovar",[
                                'options'=>[
                                    "label"=>"Новый товар"
                                ],
                                "attributes"=>[
                                    "onclick"=>"newTovar(0)",
                                    "class"=>"btn btn-primary btn-sm",
                                ]
                            ]),
                            RowModelHelper::button("new_Tovar1",[
                                'options'=>[
                                    "label"=>"Новый товар с характеристиками"
                                ],
                                "attributes"=>[
                                   // "onclick"=>"newTovar(1)",
                                    "onclick"=>"alert('Функционал пока не поддерживается, идут работы')",
                                    "class"=>"btn btn-secondary btn-sm",
                                ]
                            ]),
                        ],
                    ],
                ],

                
                "colModel" => [
                    ColModelHelper::text("id",["label"=>"ID","width"=>80]),
                    ColModelHelper::text("name",["label"=>"Название товара","width"=>300]),
                    ColModelHelper::text("sku",["label"=>"Артикул","width"=>150]),
                    ColModelHelper::text("url",["label"=>"URL карточки",
                        "width"=>250,
                       
                    ]),
                    ColModelHelper::text("poz",["label"=>"Порядок","width"=>70,"editable"=>false]),
                    ColModelHelper::checkbox("public",["label"=>"Публ.","width"=>50]),
                    
                    ColModelHelper::jscellActions("myaction",["formatoptions"=>["onEdit"=>"editTovar","onDel"=>"delTovar"]]),
                ],
            ],
        ],
];