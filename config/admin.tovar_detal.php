<?php
namespace Mf\Catalog;



return [

        "type" => "itabs",
        //"description"=>"",
        "options" => [
            "container" => "tovar_detal",
            "tabs"=>[
                [
                    "label"=>"Товар",
                    "interface"=>"catalog_tovar"
                ],
                [
                    "label"=>"Анонс",
                    "interface"=>"users_profile"
                ],
                [
                    "label"=>"Подробно",
                    "interface"=>"users_profile"
                ],
                [
                    "label"=>"Разделы",
                    "interface"=>"users_profile"
                ],

            ],

        ],
];