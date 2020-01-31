<?php
/*
* помощник view для вывода фильтра товара
*/

namespace Mf\Catalog\View\Helper;


use Laminas\View\Helper\AbstractHelper;
//use ADO\Service\RecordSet;
use Exception;
use Laminas\Form\Element;
use Laminas\Form\Form;

/**
 * помощник - вывода фильтра товара
 */
class FilterTovar extends AbstractHelper 
{
    protected $connection;
    protected $cache;
    protected $_default=[
    ];


/*конструктор
*параметры передаются из фабрики
*/
public function __construct ($connection,$cache)
  {
    $this->connection=$connection;
    $this->cache=$cache;
  }

/**
* $catalog_node_id - ID текущего узла каталога
*/
public function __invoke(int $catalog_node_id)
{
    $form = new Form('filter');
    $form->setAttribute('method', 'get');
    /*получить массив общих параметров которые имеются для данной категории товара*/
    $rs=$this->connection->Execute("select 
        cp.id, cp.name, cp.widget
        from  catalog_category2tovar c2t,catalog_tovar_properties ctp, catalog_properties cp
            where 
                cp.id=ctp.catalog_properties and
                ctp.catalog_tovar=c2t.catalog_tovar and
                c2t.catalog_category={$catalog_node_id} and
                cp.public>0 and 
                cp.widget >''
                    group by cp.id
                        order by cp.poz");
    $properties=$rs->fetchEntityAll();
    //\Admin\Debug::dump($properties);
    /*наполним значениями из того что имеется
     
    */
    $catalog_properties=[];         //это массив ID из catalog_properties
    foreach ($properties as $pr){
        $catalog_properties[]=$pr->getId();
        
    }
    
    $rsv=$this->connection->Execute("
        select * from catalog_tovar_properties 
            where 
                catalog_properties in(".implode(",",$catalog_properties).") and
                catalog_tovar in (select catalog_tovar from catalog_category2tovar where catalog_category={$catalog_node_id})
                    group by value");
    
    $properties_v=$rsv->fetchEntityAll();
    $properties_values=[];
    //перепишем массив со значениями, что бы ключ был равен ID параметра товара
    foreach ($properties_v as $prv){
        //снимаем сериализацию и пишем либо строку/число либо массив для множественного значения
        $prv->setValue(unserialize($prv->getValue()));
        $properties_values[$prv->getCatalog_properties()][]=$prv;
    }
    //теперь заносим в список свойст товара сами значения
    //+ создадим элементы формы для визуализации фильтра
    foreach ($properties as $k=>$pr){
        //$properties[$k]->setValues($properties_values[$pr->getId()]);
        switch ($pr->getWidget()){
            case "MultiCheckbox":{
                $el=new Element\MultiCheckbox("f_".$pr->getId());
                $el->setLabel($pr->getName());
                $arr=[];
                foreach ($properties_values[$pr->getId()] as $v){
                    $arr[$v->getValue()]=$v->getValue();
                }
                $el->setValueOptions($arr);
                //\Admin\Debug::dump($properties_values[$pr->getId()]);
                $form->add($el);
                break;
            }
        }

    }

    
    //\Admin\Debug::dump($properties);
    
     $el=new Element\Submit("dofilter");
    $el->setValue("Применить фильтр");
    $form->add($el);
    $html="";
    $form->prepare();
    $view=$this->getView();
    $html.=$view->form($form);
    
    
    
    return $catalog_node_id."FILTER".$html;
}

}