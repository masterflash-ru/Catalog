<?php


namespace Mf\Catalog\Service\Admin\Zform\Plugin;

use Admin\Service\Zform\Plugin\AbstractPlugin;
use  Admin\Service\Zform\RowModelHelper;
use ADO\Service\RecordSet;
use Exception;

class CatalogDopProperties extends AbstractPlugin
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
        $this->connection->Execute("insert into catalog_tovar_gabarits (catalog_tovar,catalog_measure_code)
                       select id,(select code from catalog_measure where is_default>0) 
                        from catalog_tovar where id={$id} 
                            and NOT EXISTS (select catalog_tovar from catalog_tovar_gabarits where catalog_tovar={$id})",$a,adExecuteNoRecords);
        
        $rs=$this->connection->Execute("select catalog_tovar_gabarits.*,
                        (select quantity from catalog_tovar where id=$id) as quantity
                            from catalog_tovar_gabarits where catalog_tovar=$id");
        
        foreach ($rs->DataColumns->Item_text as $column_name=>$columninfo) {
            $rez[$column_name]=$rs->Fields->Item[$column_name]->Value;
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
        //общий остаток
        $a=0;
        $quantity=(int)$postParameters["quantity"];
        $this->connection->Execute("update catalog_tovar set quantity={$quantity} where id=$id",$a,adExecuteNoRecords);
        

        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open("select * from catalog_tovar_gabarits where catalog_tovar=$id",$this->connection);
        $postParameters["coefficient"]=(int)$postParameters["coefficient"];
        if (empty($postParameters["coefficient"])){
            $postParameters["coefficient"]=1;
        }
        $rs->Fields->Item["coefficient"]->Value=$postParameters["coefficient"];
        $rs->Fields->Item["weight"]->Value=$postParameters["weight"];
        $rs->Fields->Item["length"]->Value=$postParameters["length"];
        $rs->Fields->Item["width"]->Value=$postParameters["width"];
        $rs->Fields->Item["height"]->Value=$postParameters["height"];
        $rs->Fields->Item["catalog_measure_code"]->Value=$postParameters["catalog_measure_code"];
        $rs->Update();
    }
}