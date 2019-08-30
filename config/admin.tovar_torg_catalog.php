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
                    "label"=>"Доп. параметры",
                    "interface"=>"tovar_torg_dop_parameters"
                ],
                [
                    "label"=>"Склады",
                    "interface"=>"tovar_torg_store"
                ],
            ],

        ],
];