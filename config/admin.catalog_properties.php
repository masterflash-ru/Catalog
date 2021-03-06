<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Admin\Service\Zform\RowModelHelper;
use Laminas\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Общие параметры товара",
        "options" => [
            "container" => "catalog_properties",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["catalog_properties"],
                    "keys"=>["catalog_properties"],
                ],

                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties",
                    "PrimaryKey"=>"id",
                ],

            ],
            "add"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties",
                    "PrimaryKey"=>"id",
                ],
                "cache" =>[
                    "tags"=>["catalog_properties"],
                    "keys"=>["catalog_properties"],
                ],

            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["catalog_properties"],
                    "keys"=>["catalog_properties"],
                ],
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_properties",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Общие параметры товара",
                "height" => "auto",
                //"width" => "1100px",
                "rowNum" => 20,
                "rowList" => [20,50,100],
                "sortname" => "poz",
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
                    ColModelHelper::text("name",["label"=>"Имя параметра","width"=>210,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::checkbox("public",["label"=>"Публ.","width"=>60]),
                    ColModelHelper::text("poz",["label"=>"Порядок","width"=>90,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::text("sysname",["label"=>"Системное имя","width"=>120,"editoptions" => ["size"=>50 ]]),
                    ColModelHelper::select("type",
                                           [
                                               "label"=>"Тип поля в админке",
                                               "editoptions"=>[
                                                   "value"=>[
                                                       "text"=>"Однострочный ввод",
                                                       "textarea"=>"Многострочная поле",
                                                       "select"=>"Выпадающий список",
                                                       "MultiCheckbox"=>"Массив чекбоксов",
                                                       "Radio"=>"Массив радиокнопок",
                                                   ]
                                               ],
                                           ]),
                    ColModelHelper::select("widget",
                                           [
                                               "label"=>"Поле в фильтре сайта",
                                               "width"=>170,
                                               "editoptions"=>[
                                                   "value"=>[
                                                       ""=>"Нет в фильтре",
                                                       "MultiCheckbox"=>"Массив чекбоксов",
                                                       "Radio"=>"Массив радиокнопок",
                                                       "select"=>"Выпадающий список",
                                                       "moneyrange"=>"Диапазон цен",
                                                   ]
                                               ],
                                           ]),
                    ColModelHelper::interfaces("id",
                                         [
                                             "label"=>"Списки",
                                             "width"=>100,
                                             "formatoptions" => [
                                                 "items"=>[
                                                    "button1"=> [
                                                        "label"=>"Варианты",
                                                        "interface"=>"/adm/universal-interface/catalog_properties_list",
                                                        "icon"=> "",
                                                        "get_parameter_name"=>"catalog_properties",
                                                        "dialog"=>[
                                                            "title"=>"Списик вариантов",
                                                            "resizable"=>true,
                                                            "closeOnEscape"=>true,
                                                            "width"=>"690",
                                                            "position"=>[
                                                                "my"=>"left top",
                                                                "at"=>"left top",
                                                                "of"=>"#contant-container"
                                                            ],

                                                        ],
                                                     ],
                                                 ],
                                             ]
                                         ]),


                    ColModelHelper::text("xml_id",
                                         [
                                             "label"=>"XML_ID",
                                             "width"=>80,
                                             "hidden" => true,
                                             "editrules"=>[
                                                 "edithidden"=>true,
                                             ],
                                             "editoptions" => ["size"=>50 ]
                                         ]),
                    
                    ColModelHelper::cellActions(),
                ],
            ],
        ],
];