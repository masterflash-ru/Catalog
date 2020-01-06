<?php


namespace Mf\Catalog\Service\Admin\Zform\Plugin;

use Admin\Service\Zform\Plugin\AbstractPlugin;
use  Admin\Service\Zform\RowModelHelper;
use ADO\Service\RecordSet;
use Exception;

class CatalogProperties extends AbstractPlugin
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
        $rez=["id"=>$id];
        $rs=$this->connection->Execute("select * from catalog_tovar_properties where catalog_tovar=$id");
        while (!$rs->EOF){
            $rez["properties_".$rs->Fields->Item["catalog_properties"]->Value]=unserialize($rs->Fields->Item["value"]->Value);
            $rs->MoveNext();
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
        $this->connection->Execute("delete from catalog_tovar_properties where catalog_tovar=$id",$a,adExecuteNoRecords);
        
        //возможные списки вариантов
        $rslist=new RecordSet();
        $rslist->CursorType =adOpenKeyset;
        $rslist->MaxRecords=0;
        $rslist->Open("select * from catalog_properties_list",$this->connection);

        //возможные варианты ID сво-в товара, что бы не выйти за пределы
        $rsprop=new RecordSet();
        $rsprop->CursorType =adOpenKeyset;
        $rsprop->MaxRecords=0;
        $rsprop->Open("select id from catalog_properties",$this->connection);

        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->MaxRecords=0;
        $rs->Open("select * from catalog_tovar_properties where catalog_tovar=$id",$this->connection);
        foreach ($postParameters as $k=>$v){
            $n=explode("_",$k);
            if (count($n)!=2 && !in_array("properties",$n)){
                continue;
            }
            //смотрим на допустимость ID св-ва
            $rsprop->Find("id=".(int)$n[1]);
            if ($rsprop->EOF){
                throw new  Exception("Идентификатор св-ва товара: ".(int)$n[1]." не найдено");
            }
        
            $rs->AddNew();
            $rs->Fields->Item["catalog_tovar"]->Value=$id;
            $rs->Fields->Item["catalog_properties"]->Value=(int)$n[1];
            $rs->Fields->Item["value"]->Value=serialize($v);
            
            //ищем список вариантов, если есть
            $rslist->Filter="catalog_properties=".(int)$n[1];
            if (!$rslist->EOF){
                $rs->Fields->Item["catalog_properties_list"]->Value=$rslist->Fields->Item["id"]->Value;
            }
            $rs->Update();
        }
    }

    
    
    /**
    * осблуживает поле формы типа DynamicArray, чтение
    * создает динамические поля путем генерации конфига в формате фабрики Laminas
    * $dynamic_element - массив конфига из динамического поля (все что внутри spec )
    * возвращает массив:
    * [
    *   "elements"  =>  [], - собственно массив элементов, в формате ZF (https://docs.zendframework.com/zend-form/quick-start/)
    *   "input_filter"=>[], - массив фильтров и валидаторов в формате ZF (https://docs.zendframework.com/zend-form/quick-start/)
    *  ]
    */
    public function ReadDynamicArray(array $dynamic_element=[])
    {
        $rs=$this->connection->Execute("select * from catalog_properties order by poz");
        $input_filter=[];
        $rez=[];

        $rslist=new RecordSet();
        $rslist->CursorType =adOpenKeyset;
        $rslist->MaxRecords=0;
        $rslist->Open("select * from catalog_properties_list",$this->connection);

        while (!$rs->EOF){
            //смотрим список
            $list=[];
            $rslist->Filter="catalog_properties=".$rs->Fields->Item["id"]->Value;
            while (!$rslist->EOF){
                $list[$rslist->Fields->Item["value"]->Value]=$rslist->Fields->Item["value"]->Value;
                $rslist->MoveNext();
            }
            
            $field=$rs->Fields->Item["type"]->Value;
            $rez[]=RowModelHelper::$field("properties_".$rs->Fields->Item["id"]->Value,[
                'options'=>[
                    "label"=>$rs->Fields->Item["name"]->Value,
                    "value_options"=>$list,
                    "empty_option"=>""
                ],
            ]);
            //разрешим пустой ввод
            $input_filter["properties_".$rs->Fields->Item["id"]->Value]= ['required' => false];
            
            $rs->MoveNext();
        }

        return ["elements"=>$rez,"input_filter"=>$input_filter];
    }

    

}