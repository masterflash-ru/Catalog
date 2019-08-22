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
                    "label"=>"Параметры (габариты)",
                    "interface"=>"tovar_torg_gabarits"
                ],
                [
                    "label"=>"Склады",
                    "interface"=>"tovar_torg_store"
                ],
            ],

        ],
];