<?php
namespace Mf\Catalog;



return [

        "type" => "itabs",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal",
            "tabs"=>[
                [
                    "label"=>"Основная информация",
                    "interface"=>"tovar_detal_base"
                ],
                [
                    "label"=>"Анонс",
                    "interface"=>"tovar_detal_anons"
                ],
                [
                    "label"=>"Подробное описание",
                    "interface"=>"tovar_detal_detal"
                ],
                [
                    "label"=>"Категории товара",
                    "interface"=>"tovar_detal_category"
                ],
                [
                    "label"=>"Торговый каталог",
                    "interface"=>"tovar_torg_catalog"
                ],

            ],

        ],
];