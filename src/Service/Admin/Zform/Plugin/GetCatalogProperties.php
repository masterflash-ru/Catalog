<?php


namespace Mf\Catalog\Service\Admin\Zform\Plugin;

use Admin\Service\Zform\Plugin\AbstractPlugin;
use  Admin\Service\Zform\RowModelHelper;
use ADO\Service\RecordSet;

class GetCatalogProperties extends AbstractPlugin
{
    protected $rez=[];

    public function __construct($connection)
    {
        $rs=$connection->Execute("select * from catalog_properties");

        $rslist=new RecordSet();
        $rslist->CursorType =adOpenKeyset;
        $rslist->MaxRecords=0;
        $rslist->Open("select * from catalog_properties_list",$connection);


        while (!$rs->EOF){
            //смотрим список
            $list=[];
            $rslist->Filter="catalog_properties=".$rs->Fields->Item["id"]->Value;
            while (!$rslist->EOF){
                $list[$rslist->Fields->Item["value"]->Value]=$rslist->Fields->Item["value"]->Value;
                $rslist->MoveNext();
            }
            
            $field=$rs->Fields->Item["type"]->Value;
            $this->rez[]=RowModelHelper::$field("properties_".$rs->Fields->Item["id"]->Value,[
                'options'=>[
                    "label"=>$rs->Fields->Item["name"]->Value,
                    "value_options"=>$list,
                ],
            ]);
            
            $rs->MoveNext();
        }

    }
    
    /**
    * создает динамические поля путем генерации конфига в формате фабрики Zend
    * $dynamic_element - массив конфига из динамического поля (все что внутри spec )
    */
    public function ReadDynamicArray(array $dynamic_element=[])
    {
        return $this->rez;
    }

    

}