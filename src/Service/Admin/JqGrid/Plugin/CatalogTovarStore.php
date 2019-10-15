<?php


namespace Mf\Catalog\Service\Admin\JqGrid\Plugin;

use Admin\Service\JqGrid\Plugin\AbstractPlugin;
use ADO\Service\RecordSet;

class CatalogTovarStore extends AbstractPlugin
{
    protected $connection;


    public function __construct($connection)
    {
        $this->connection=$connection;
    }
    
    /**
    * чтение интерфейса
    * пример данных:
array(7) {
  ["tovar_id"] => string(1) "2"
  ["catalog_store"] => string(1) "1"
  ["quantity_id"] => string(0) ""
  ["quantity"] => string(1) "2"
  ["xml_id"] => string(9) "234345235"
  ["oper"] => string(4) "edit"
  ["id"] => string(1) "1"
}
    */
    public function iedit(array $postParameters)
    {
        //\Zend\Debug\Debug::dump($postParameters);
        $tovar_id=(int)$postParameters["tovar_id"];
        $quantity_id=(int)$postParameters["quantity_id"];
        
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open("select * from catalog_tovar_store where catalog_tovar=$tovar_id",$this->connection);
        if (empty($postParameters["quantity_id"])){
            $rs->AddNew();
        } else {
            $rs->Find("id={$quantity_id}");
        }
        $rs->Fields->Item["catalog_store"]->Value=(int)$postParameters["catalog_store"];
        $rs->Fields->Item["quantity"]->Value=(int)$postParameters["quantity"];
        $rs->Fields->Item["catalog_tovar"]->Value=$tovar_id;
        $rs->Fields->Item["catalog_store"]->Value=(int)$postParameters["catalog_store"];
        $rs->Update();
    }
}