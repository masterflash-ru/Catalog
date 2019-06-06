<?php
namespace Mf\Catalog;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Admin\Service\Zform\RowModelHelper;
use Zend\Json\Expr;



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
                    "sql"=>"select * from catalog_tovar",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["catalog_tovar"],
                    "keys"=>["catalog_tovar"],
                ],

                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar",
                    "PrimaryKey"=>"id",
                ],

            ],
            "add"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar",
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
                    //"editOptions"=>NavGridHelper::editOptions(),
                   // "addOptions"=>NavGridHelper::addOptions(),
                    //"delOptions"=>NavGridHelper::delOptions(),
                ],
                //дополнительные кнопки в панель сетки
                "navButtonAdd"=>[
                    NavGridHelper::ButtonAdd(["caption"=>"Заголовок","title"=>"Добавить товар","onClickButton"=>new Expr('newTovar')]),
                    NavGridHelper::ButtonAdd(["caption"=>"Заголовок1","title"=>"Добавить товар1"]),
                ],
                
                //"colMenu"  =>  true ,
                /*область перед телом сетки, toolbar
                * все настройки как в Zform
                */
                "toolbar"=> [true,"top"],
                "toolbarModel"=>[
                    "rowModel" => [
                        'elements' => [
                            RowModelHelper::button("newTovar",[
                                'options'=>[
                                    "label"=>"Новый товар"
                                ],
                                "attributes"=>[
                                    "onclick"=>"newTovar()",
                                    "class"=>"btn btn-primary btn-sm",
                                ]
                            ]),
                            RowModelHelper::button("newTovar1",[
                                'options'=>[
                                    "label"=>"Новый товар с характеристиками"
                                ],
                                "attributes"=>[
                                    "onclick"=>"newTovar()",
                                    "class"=>"btn btn-secondary btn-sm",
                                ]
                            ]),
                        ],
                    ],
                ],

                
                "colModel" => [
                    ColModelHelper::text("id",["label"=>"ID","width"=>80,"editable"=>false]),
                    ColModelHelper::text("name",["label"=>"Название товара","editable"=>false]),
                    ColModelHelper::text("url",["label"=>"URL карточки",
                        "width"=>200,
                        "hidden"=>true,
                        "editrules"=>[
                            "edithidden"=>true,
                        ],
                        "plugins"=>[
                            "edit"=>[
                                "translit"=>[
                                    "source"=>"name"
                                ],
                            ],
                            "add"=>[
                                "translit"=>[
                                    "source"=>"name"
                                ],
                            ],
                        ],
                       "editable"=>false,
                    ]),
                    ColModelHelper::text("poz",["label"=>"Порядок","width"=>70,"editable"=>false]),
                    ColModelHelper::checkbox("public",["label"=>"Публ.","width"=>50]),
                    ColModelHelper::interfaces("id",
                                         [
                                             "label"=>"Редактировать",
                                             "width"=>160,
                                             "formatoptions" => [
                                                 "items"=>[
                                                    "button1"=> [
                                                        "label"=>"Подробности",
                                                        "interface"=>"/adm/universal-interface/tovar_detal",
                                                        "icon"=> "ui-icon-contact",
                                                        "dialog"=>[
                                                            "title"=>"Подробности",
                                                            "resizable"=>true,
                                                            "closeOnEscape"=>true,
                                                            "width"=>"680",
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

                    
                    ColModelHelper::jscellActions("myaction",["formatoptions"=>["onEdit"=>"editTovar"]]),
                ],
            ],
        ],
];