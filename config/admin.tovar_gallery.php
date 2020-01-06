<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Laminas\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Список товара для фотогалереи",
        "options" => [
            "container" => "tovar_gallery",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Фотогалерея товара",
                "height" => "auto",
                //"width" => 1000,
                "rowNum" => 50,
                "rowList" => [50,100],
                "sortname" => "id",
                "sortorder" => "asc",
                "viewrecords" => true,
                "autoencode" => false,
                "hidegrid" => false,
                "toppager" => true,
                
                /*дает доп строку в конце сетки, из данных туда можно ставить итоги какие-либо*/
                //"footerrow"=> true, 
                //"userDataOnFooter"=> true,
               
                // "multiselect" => true,
                //"onSelectRow"=> new Expr("editRow"), //клик на строке вызов строчного редактора
        
                
                "rownumbers" => false,
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false,"add"=>false,"edit"=>false,"del"=>false]),
                ],
                "colModel" => [


                    ColModelHelper::text("id",["label"=>"ID","width"=>90]),
                    ColModelHelper::text("name",["label"=>"Наименование","width"=>500]),
                    ColModelHelper::text("sku",["label"=>"Артикул","width"=>150]),
                    ColModelHelper::interfaces("id",
                                         [
                                             "label"=>"Галерея фото",
                                             "width"=>120,
                                             "formatoptions" => [
                                                 "items"=>[
                                                    "button1"=> [
                                                        "label"=>"Редактор",
                                                        "interface"=>"/adm/universal-interface/tovar_catalog_gallery",
                                                        "get_parameter_name"=>"catalog_tovar",
                                                        "icon"=> "",
                                                        "dialog"=>[
                                                            "title"=>"Фото галерея",
                                                            "resizable"=>true,
                                                            "closeOnEscape"=>true,
                                                            "width"=>"680",
                                                            /*"position"=>[
                                                                "my"=>"left top",
                                                                "at"=>"left top",
                                                                "of"=>".fixed-top-item"
                                                            ],*/

                                                        ],
                                                     ],
                                                 ],
                                             ]
                                         ]),


            
                
                ],
            ],
        ],
];