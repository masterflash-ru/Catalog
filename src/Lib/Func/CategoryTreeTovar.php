<?php
//Генерирует выпадающий список в разделе ввода товаров
namespace Mf\Catalog\Lib\Func;


use Admin\Lib\Simba;

class CategoryTreeTovar
{


public function __invoke ($obj,$infa,$struct_arr,$pole_type,$pole_dop,$tab_name,$idname,$const,$id,$action)
{//коррекция пути при чтении поля для таблицы бибилиотека ява-скриптов
/*
входные параметры:
$obj - Экземпляр объекта с интерфейсом
$infa сама информация
$struct_arr
$pole_type - тип поля (идентификатор)
$obj->pole_dop - массив данных доп. поля
$tab_name - имя редактируемой таблицы СУБД
$idname - имя идентификатора (уникального поля) таблицы СУБД
$const - массив констант для поля
$id - идентификатор редактирумой строки таблицы
$action - действие 0-чтение, 1-запись, 2- удаление
*/

    $this->tovtree1($obj,0,0);

    return $infa;
}


protected function tovtree1($obj,$subid,$lev)
{
    //получить список подразделов
    $razd=simba::queryAllRecords("SELECT id,subid,level, concat(repeat('-',level),' ',name) as name  
        FROM  catalog_category  
            where level=$lev and subid=".$subid." order by poz,name"); 
    $count=simba::numRows();
    for ($i=0; $i<$count;$i++){
        $obj->dop_sql['name'][]=$razd['name'][$i];
        $obj->dop_sql['id'][]=$razd['id'][$i];
        $this->tovtree1 ($obj,$razd['id'][$i],$lev+1);
    }
}





}