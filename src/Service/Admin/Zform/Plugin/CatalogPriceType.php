<?php


namespace Mf\Catalog\Service\Admin\Zform\Plugin;

use Admin\Service\Zform\Plugin\AbstractPlugin;
use  Admin\Service\Zform\RowModelHelper;
use ADO\Service\RecordSet;
use Admin\Service\Zform\Plugin\SelectFromDb;
use Exception;

class CatalogPriceType extends AbstractPlugin
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection=$connection;

    }
    
    /**
    * обслуживает весь интерфейс ввода/вывода - операция чтение
    * $get - массив GET параметров если есть
    * возвращает массив с данными (ассоциативный)
    */
    public function iread(array $get)
    {
        $id=(int)$get["id"];
        if (empty($id)){
            throw new  Exception("Ошибка в <b>".__CLASS__."</b> метод <b>".__METHOD__."</b> ID товара не может быть пустым");
        }
        $rez=["id"=>$id,"catalog_tovar"=>$id];
        $rst=$this->connection->Execute("select * from catalog_price_type order by is_base desc");
        
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->MaxRecords=0;
        $rs->Open("select * from catalog_tovar_currency where catalog_tovar=$id",$this->connection);

        while (!$rst->EOF){
            $rs->Filter="catalog_price_type=".(int)$rst->Fields->Item["id"]->Value;
            $rez["catalog_currency__".$rst->Fields->Item["id"]->Value]=$rs->Fields->Item["catalog_currency"]->Value;
            $rez["value__".$rst->Fields->Item["id"]->Value]=$rs->Fields->Item["value"]->Value;
            $rez["nds__".$rst->Fields->Item["id"]->Value]=$rs->Fields->Item["nds"]->Value;
            $rst->MoveNext();
        }
        
        return $rez;
    }

    /**
    * обслуживает весь интерфейс ввода/вывода - операция запись
    * $get - массив GET параметров если есть
    * возвращает массив с данными (ассоциативный)
    */
    public function iedit(array $postParameters,array $get=[])
    {
        $id=(int)$get["id"];
        if (empty($id)){
            throw new  Exception("Ошибка в <b>".__CLASS__."</b> метод <b>".__METHOD__."</b> ID товара не может быть пустым");
        }
        //удалим старое
        $a=0;
        $this->connection->Execute("delete from catalog_tovar_currency where catalog_tovar=$id",$a,adExecuteNoRecords);
        

        //возможные варианты ID сво-в товара, что бы не выйти за пределы
        $rst=new RecordSet();
        $rst->CursorType =adOpenKeyset;
        $rst->MaxRecords=0;
        $rst->Open("select id from catalog_price_type",$this->connection);

        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->MaxRecords=0;
        $rs->Open("select * from catalog_tovar_currency where catalog_tovar=$id",$this->connection);
        $rec=[];
        foreach ($postParameters as $k=>$v){
            $n=explode("__",$k);
            if (count($n)!=2 && !in_array("properties",$n)){
                continue;
            }
            //смотрим на допустимость ID типа цены
            $rst->Find("id=".(int)$n[1]);
            if ($rst->EOF){
                throw new  Exception("Недопустимый тип цены товара: ".(int)$n[1]." - не найдено");
            }
            //собираем в массив что бы записать (т.к. другая плоскость хранения в базе)
            $rec[(int)$n[1]][$n[0]]=$v;
        }
        foreach ($rec as $catalog_price_type=>$item){
            $rs->AddNew();
            $rs->Fields->Item["catalog_tovar"]->Value=$id;
            $rs->Fields->Item["catalog_price_type"]->Value=(int)$catalog_price_type;
            foreach ($item as $field=>$val){
                $rs->Fields->Item[$field]->Value=$val;
            }
            $rs->Update();
        }
    }


    /**
    * осблуживает поле формы типа DynamicArray, чтение
    * создает динамические поля путем генерации конфига в формате фабрики Zend
    * $dynamic_element - массив конфига из динамического поля (все что внутри spec )
    */
    public function ReadDynamicArray(array $dynamic_element=[])
    {
        $rs=$this->connection->Execute("select * from catalog_price_type order by is_base desc");

        while (!$rs->EOF){
            $rez[]=$rez[]=RowModelHelper::caption(null,[
                'options'=>[
                    "label"=>$rs->Fields->Item["name"]->Value,
                ],
            ]);
            
            $rez[]= RowModelHelper::select("catalog_currency__".$rs->Fields->Item["id"]->Value,[
                            'options'=>[
                                "label"=>"Валюта:"
                            ],
                            "plugins"=>[
                                "rowModel"=>[//плагин срабатывает при генерации формы до ее вывода
                                    SelectFromDb::class=>[
                                        "sql"=>"select currency as id, currency as name from catalog_currency order by poz"
                                    ],
                                ],
                            ],

                        ]);

            $rez[]=RowModelHelper::text("value__".$rs->Fields->Item["id"]->Value,[
                'options'=>[
                    "label"=>"Значение",
                ],
            ]);
            $rez[]=RowModelHelper::checkbox("nds__".$rs->Fields->Item["id"]->Value,['options'=>["label"=>"НДС включен в цену"]]);
            
            $rs->MoveNext();
        }

        return $rez;
    }

    

}