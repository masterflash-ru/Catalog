<?php
namespace Mf\Catalog\Lib\Func;

use Admin\Lib\Simba;



class ChangeLevel
{



public function __invoke ($obj,$infa,$struct0,$struct2,$tab_name,$const,$row_item,$a,$b,$action)
{
    $infa=(int)$infa;
    if (empty($infa)){
        return $infa;
    }
    $r=simba::queryOneRecord("select level from catalog_category where id={$infa}");
    $r["level"]++;
    simba::query("update catalog_category set level=1+".$r["level"]." where id={$b}");
    return $infa;
}




    
}
