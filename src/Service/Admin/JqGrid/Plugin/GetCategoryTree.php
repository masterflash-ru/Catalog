<?php


namespace Mf\Catalog\Service\Admin\JqGrid\Plugin;

use Admin\Service\Zform\Plugin\AbstractPlugin;
use Laminas\Form\FormInterface;
use ADO\Service\RecordSet;

class GetCategoryTree extends AbstractPlugin
{
    protected $rs;
    protected $rez=[];

    public function __construct($connection)
    {
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->MaxRecords=0;
        $rs->Open("select id,subid,level, concat(repeat('-',level),' ',name) as name from catalog_category order by poz",$connection);
        $this->rs=$rs;
    }
    
    /**
    * преобразование элементов rowModel, например, для генерации списков
    * $rowModel - элемент $rowModel из конфигурации
    * $form - экземпляр формы, в нее нужно заносить информацию
    */
    public function rowModel(array $rowModel, FormInterface $form)
    {
        $this->tovtree(0,0);
        $form->get($rowModel["name"])->setValueOptions($this->rez);
    }

    
    /**
    * обход дерева и для наполнения элмента Select
    */
    protected function tovtree($subid=0,$lev=0)
    {
        $rs=clone $this->rs;
        $lev=(int)$lev;
        $subid=(int)$subid;
        $rs->Filter="level=$lev and subid=$subid";
        while (!$rs->EOF){
            $this->rez[$rs->Fields->Item["id"]->Value]=$rs->Fields->Item["name"]->Value;
            $this->tovtree($rs->Fields->Item["id"]->Value,$rs->Fields->Item["level"]->Value+1);
            $rs->MoveNext();
        }
    }


}