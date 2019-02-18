<?php
namespace Mf\Catalog\Lib\Func;
use Admin\Lib\Simba;

class GetNodeList
{

    protected $id;

function __invoke($obj,$infa,$struct_arr,$pole_type,$pole_dop,$tab_name,$idname,$number,$id,$action)
{
    $this->id=$id[0];
    $this->tovtree1($obj,0,0);
    return $infa;
}


protected function tovtree1($obj,$subid,$lev)
{
    //получить список подразделов
    $razd=simba::queryAllRecords("SELECT id,subid,level, concat(repeat('-',level),' ',name) as name  
            FROM  catalog_category 
                where 
                    level=$lev and 
                    subid=".$subid." 
                        order by poz,name
                "); 
    $count=simba::numRows();
    for ($i=0; $i<$count;$i++){
        $obj->dop_sql['name'][]=$razd['name'][$i];
        $obj->dop_sql['id'][]=$razd['id'][$i];
        $this->tovtree1 ($obj,$razd['id'][$i],$lev+1);
    }
}


}