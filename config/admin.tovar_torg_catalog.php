<?php
namespace Mf\Catalog;



return [

        "type" => "itabs",
        //"description"=>"",
        "options" => [
            "container" => "tovar_torg_catalog",
            "tabs"=>[
                [
                    "label"=>"Цены",
                    "interface"=>"tovar_torg_money"
                ],
                [
                    "label"=>"Параметры",
                    "interface"=>"tovar_detal_anons"
                ],
                [
                    "label"=>"Склады",
                    "interface"=>"tovar_detal_detal"
                ],
            ],

        ],
];