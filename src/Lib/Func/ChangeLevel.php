<?php
namespace Mf\Catalog\Lib\Func;

use Admin\Lib\Simba;



class ChangeLevel
{



public function __invoke ($obj,$infa,$struct0)
{
    
    
    $this->tovtree1(0,0);

}



protected function tovtree1($subid,$lev)
{
    //получить список подразделов
    $razd=simba::queryAllRecords("SELECT id,subid,level FROM  catalog_category  where subid=".$subid); 
    $count=simba::numRows();
    for ($i=0; $i<$count;$i++){
        $this->tovtree1 ($razd['id'][$i],$lev+1);
    }
    simba::query("update catalog_category set level=$lev  where subid=".$subid); 
}

    
}
