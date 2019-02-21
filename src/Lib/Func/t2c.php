<?php
namespace Mf\Catalog\Lib\Func;


use Admin\Lib\Simba;


class  t2c
{


public function __invoke ($obj,$infa,$struct_arr,$pole_type,$pole_dop,$tab_name,$idname,$const,$id,$action)
{

Simba::$connection->BeginTrans();
	simba::query("delete from catalog_category2tovar where catalog_tovar='$id'");

    if (!empty($infa) && is_array($infa)){
        foreach ($infa as $tc){
            if (empty($tc)){continue;}
                simba::replaceRecord (array(
                'catalog_tovar'=>(int)$id,
                'catalog_category'=>(int)$tc
            ),'catalog_category2tovar');
        }
    }
Simba::$connection->CommitTrans() ;
return $infa;
}

}