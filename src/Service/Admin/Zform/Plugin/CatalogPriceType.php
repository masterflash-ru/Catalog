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
    protected $config;

    public function __construct($connection,$config)
    {
        $this->connection=$connection;
        $this->config=$config;
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
        $rst=$this->connection->Execute("select * from catalog_price_type order by name");
        
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->MaxRecords=0;
        $rs->Open("select * from catalog_tovar_currency where catalog_tovar=$id",$this->connection);

        while (!$rst->EOF){
            $rs->Filter="catalog_price_type=".(int)$rst->Fields->Item["id"]->Value;
            if (!$rs->EOF){//есть значения
                $rez["catalog_currency__".$rst->Fields->Item["id"]->Value]=$rs->Fields->Item["catalog_currency"]->Value;
                $rez["value__".$rst->Fields->Item["id"]->Value]=$rs->Fields->Item["value"]->Value;
                $rez["vat_in__".$rst->Fields->Item["id"]->Value]=$rs->Fields->Item["vat_in"]->Value;
                $rez["vat_value__".$rst->Fields->Item["id"]->Value]=$rs->Fields->Item["vat_value"]->Value;
            } else {
                $rez["vat_in__".$rst->Fields->Item["id"]->Value]=$this->config["catalog"]["default"]["vat_in"];
                $rez["vat_value__".$rst->Fields->Item["id"]->Value]=$this->config["catalog"]["default"]["vat_value"];
                $rez["catalog_currency__".$rst->Fields->Item["id"]->Value]=$this->config["catalog"]["default"]["currency"];
            }
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
        $rst->Open("select id,is_base from catalog_price_type",$this->connection);

        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->MaxRecords=0;
        $rs->Open("select * from catalog_tovar_currency where catalog_tovar=$id",$this->connection);
        $rec=[];
        $vat_in=0;
        $vat_value=0;
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
        //обновим все записи значения НДС для не базовых типов цен
        $a=0;
        $this->connection->Execute("create temporary table tmp (vat_in int(11), vat_value decimal(11,2)) ENGINE=MEMORY DEFAULT CHARSET=utf8",$a,adExecuteNoRecords);
        $this->connection->Execute("insert into tmp 
                select vat_in, vat_value from catalog_tovar_currency 
                    where  catalog_tovar=$id and 
                    catalog_price_type=(select id from catalog_price_type where is_base=1 limit 1)",$a,adExecuteNoRecords);
        $this->connection->Execute("update catalog_tovar_currency c,tmp 
                                set c.vat_in=tmp.vat_in, c.vat_value=tmp.vat_value
                                    where c.catalog_tovar=$id",$a,adExecuteNoRecords);
        $this->connection->Execute("drop table tmp",$a,adExecuteNoRecords);
    }


    /**
    * осблуживает поле формы типа DynamicArray, чтение
    * создает динамические поля путем генерации конфига в формате фабрики Zend
    * $dynamic_element - массив конфига из динамического поля (все что внутри spec )
    * возвращает массив:
    * [
    *   "elements"  =>  [], - собственно массив элементов, в формате ZF (https://docs.zendframework.com/zend-form/quick-start/)
    *   "input_filter"=>[], - массив фильтров и валидаторов в формате ZF (https://docs.zendframework.com/zend-form/quick-start/)
    *  ]
    */
    public function ReadDynamicArray(array $dynamic_element=[])
    {
        $rs=$this->connection->Execute("select * from catalog_price_type  order by is_base desc, name asc");
        $input_filter=[];
        $vat_values=$this->config["catalog"]["vat_values"];

        while (!$rs->EOF){
            $rez[]=RowModelHelper::caption(null,[
                'options'=>[
                    "label"=>$rs->Fields->Item["name"]->Value,
                ],
            ]);
            
            if ($rs->Fields->Item["is_base"]->Value>0){
                $rez[]= RowModelHelper::select("vat_value__".$rs->Fields->Item["id"]->Value,[
                            'options'=>[
                                "label"=>"Ставка НДС:",
                                "value_options"=>$vat_values,
                            ],
                        ]);
                $rez[]= RowModelHelper::checkbox("vat_in__".$rs->Fields->Item["id"]->Value,['options'=>["label"=>"НДС включен в цену"]]);

            }
            
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
            
            $rs->MoveNext();
        }

        return ["elements"=>$rez,"input_filter"=>$input_filter];
    }

    

}