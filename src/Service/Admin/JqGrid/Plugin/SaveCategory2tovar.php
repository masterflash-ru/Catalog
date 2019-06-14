<?php


namespace Mf\Catalog\Service\Admin\JqGrid\Plugin;

use Admin\Service\Zform\Plugin\AbstractPlugin;
use Zend\Form\FormInterface;
use ADO\Service\RecordSet;

class SaveCategory2tovar extends AbstractPlugin
{
    protected $connection;


    public function __construct($connection)
    {
        $this->connection=$connection;
    }
    
    /**
    * собственно запись категорий в связку категории-товар
    */
    public function edit($postParameters,$getParameters)
    {
        $id=(int)$getParameters["id"];
        //удалим что было раньше
        $this->connection->BeginTrans();
        $this->connection->Execute("delete from catalog_category2tovar where catalog_tovar=$id");
        $this->connection->CommitTrans();
        $this->connection->BeginTrans();
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open("select * from catalog_category2tovar where catalog_tovar=$id",$this->connection);
        
        if (isset($postParameters["category"]) && is_array($postParameters["category"])){
            foreach ($postParameters["category"] as $category){
                $rs->AddNew();
                $rs->Fields->Item["catalog_tovar"]->Value=$id;
                $rs->Fields->Item["catalog_category"]->Value=$category;
                $rs->Update();
            }
        }
        $this->connection->CommitTrans();
        //\Zend\Debug\Debug::dump($postParameters);
        //\Zend\Debug\Debug::dump($getParameters);
    }



}