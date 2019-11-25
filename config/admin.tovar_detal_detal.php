<?php
namespace Mf\Catalog;

use Admin\Service\Zform\RowModelHelper;
use Zend\Validator;
use Zend\Filter;



return [

        "type" => "izform",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal_detal",
            "podval" =>"",
            "container-attr"=>"style='width:800px'",
        
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select catalog_tovar.*, id as img from catalog_tovar where id=:id",  
                ],
            ],
            "edit"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select * from catalog_tovar",  
                ],
                "cache" =>[
                    "tags"=>["catalog_tovar"],
                    "keys"=>["catalog_tovar"],
                ],

            ],
            
            /*поведение формы*/
            "actionsEvent"=>[
                /*что делать после успешной записи формы*/
                //"FormAfterSubmitOkEvent"=>'$("#catalog_tovar").trigger("reloadGrid");',
            ],

            /*внешний вид*/
            "layout"=>[
                "caption" => "Подробное описание товара (карточка)",
                "rowModel" => [
                    'elements' => [
                        RowModelHelper::uploadimage("img",
                                              [
                                                  'options'=>["label"=>"Фото товара"],
                                                  "plugins"=>[
                                                      "read"=>[
                                                          "Images" =>[
                                                              "storage_item_name" => "catalog_tovar_detal", //имя секции в хранилище
                                                              "storage_item_rule_name"=>"admin_img"         //имя правила из хранилища
                                                          ],
                                                      ],
                                                      "edit"=>[
                                                          "Images"=>[
                                                              "storage_item_name" => "catalog_tovar_detal",              //имя секции в хранилище
                                                              "image_id"=>"id"
                                                          ],
                                                      ],

                                                  ],
                                              ]),

                        RowModelHelper::ckeditor("info",['options'=>["label"=>"Подробное описание"]]),
                        RowModelHelper::textarea("title",['options'=>["label"=>"TITLE"],'attributes' => ["rows"=>3]]),
                        RowModelHelper::textarea("keywords",['options'=>["label"=>"KEYWORDS"],'attributes' => ["rows"=>3]]),
                        RowModelHelper::textarea("description",['options'=>["label"=>"DESCRIPTION"],'attributes' => ["rows"=>6]]),
                        RowModelHelper::submit("submit",[
                            'attributes'=>['value' => 'Записать'],
                        ]),
                        //это ID товара
                        RowModelHelper::hidden("id"),
                    ],
                    /*конфигурация фильтров и валидаторов*/
                    'input_filter' => [
                        "img" => [
                            'required' => false,
                            'filters' => [
                                [ 'name' => Filter\File\RenameUpload::class,
                                    'options'=>[
                                        'target'    => './data/datastorage',
                                        'use_upload_name' => true,
                                        'overwrite' => true
                                    ]
                                ],
                            ],
                            'validators' => [
                                [ 'name' => Validator\File\UploadFile::class ],
                                [ 'name' => Validator\File\IsImage::class ],
                            ],
                        ],
                    ],//input_filter

                ],
            ],
        ],
];