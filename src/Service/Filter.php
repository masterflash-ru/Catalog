<?php
/*
модуль работы с фильтром товара

*/
namespace Mf\Catalog\Service;
use ADO\Service\RecordSet;
use ADO\Service\Command;

use Laminas\Form\Element;
use Laminas\Form\Form;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Input;

use Exception;

    
class Filter
{
	public $connection;
	public $cache;
    /*флаг применения фильтра*/
    protected $do_filter=false;
    
    /**кешированая форма*/
    protected $forma;


    public function __construct($connection,$cache)
    {
        $this->connection=$connection;
        $this->cache=$cache;
    }


    
    
    /**
    * фильтр был применен?
    * возвращает true|false (да/нет)
    */
    public function isFiltered()
    {
        return $this->do_filter;
    }
    
    
    
    
    /**
    * создать форму фильтра пригодную для вывода и работы с данными
    * $catalog_node_id - ID узла каталога
    * возвращает экземпляр Laminas\Form\Form
    * если данные формы не валидны (например, подмена), исключение, 
    */
    public function createForm(int $catalog_node_id)
    {
        if (empty($this->forma)) {
             $this->forma=$this->_createForm($catalog_node_id);
        }
        return $this->forma;
    }
    

    /**
    * создать форму фильтра пригодную для вывода и работы с данными
    * $catalog_node_id - ID узла каталога
    * возвращает экземпляр Laminas\Form\Form
    * если данные формы не валидны (например, подмена), исключение, 
    */
    protected function _createForm(int $catalog_node_id)
    {
        $form = new Form('filter');

        /*получить массив общих параметров которые имеются для данной категории товара*/
        $properties=$this->getCatalogProperties($catalog_node_id);

        //это массив ID из catalog_properties
        $catalog_properties=array_map(function($pr){return $pr->getId();},$properties);

        //значения параментров
        $properties_v=$this->getTovarValueProperties($catalog_properties,$catalog_node_id);
        $properties_values=[];
        //перепишем массив со значениями, что бы ключ был равен ID параметра товара
        foreach ($properties_v as $prv){
            $properties_values[$prv->getCatalog_properties()][]=$prv;
        }

        //флаг того что фильтр применен
        $this->do_filter=false;
        $inputFilter = new InputFilter();
        //создаем элементы формы, заносим туда правила фильтрации и варианты значений
        foreach ($properties as $k=>$pr){
            switch (strtolower($pr->getWidget())){
                case "multicheckbox":{
                    $el=new Element\MultiCheckbox("f_".$pr->getId());
                    break;
                }
                case "radio":{
                    $el=new Element\Radio("f_".$pr->getId());
                    break;
                }
                case "select":{
                    $el=new Element\Select("f_".$pr->getId());
                    break;
                }
            }
            
            $nameInput = new Input("f_".$pr->getId());
            $nameInput->setRequired(false);
            $el->setLabel($pr->getName());
            $arr=[];
            foreach ($properties_values[$pr->getId()] as $v){
                $arr[$v->getValue()]=$v->getValue();
            }
            $el->setValueOptions($arr);
            $form->add($el);
            $inputFilter->add($nameInput);
            if (!empty($_GET["f_".$pr->getId()])){
                $this->do_filter=true;
            }
        }
        $form->setInputFilter($inputFilter);
        $form->setData($_GET);
        //если фильтр применили, проверим форму на валидность
        if (!$form->isValid() && $this->do_filter){
            throw new Exception("Данные фильтра не валидны");
        }
        return $form;
    }
    
    

    /**
    * получить массив имен, ID параметров которые доступны для данной категории товара
    * $catalog_node_id - ID узла каталога
    * возвращает массив объектов с наполненными св-вами из таблицы catalog_properties
    * св-ва:  ID, name, widget
    */
    public function getCatalogProperties(int $catalog_node_id)
    {
        $rs=$this->connection->Execute("select 
            cp.id, cp.name, cp.widget
            from  catalog_category2tovar c2t,catalog_tovar_properties ctp, catalog_properties cp
                where 
                    cp.id=ctp.catalog_properties and
                    ctp.catalog_tovar=c2t.catalog_tovar and
                    c2t.catalog_category={$catalog_node_id} and
                    cp.public>0 and 
                    cp.widget >'' and
                    ctp.value>''
                        group by cp.id
                            order by cp.poz");
        $properties=$rs->fetchEntityAll();
        return $properties;
    }
    
    /**
    * получить значения для наполнения виджетов фильтра
    * $catalog_properties - массив ID параметров (из таблицы catalog_properties)
    * $catalog_node_id - ID узла каталога 
    * возвращает массив универсальных объектов с заполненными св-вами из таблицы catalog_tovar_properties
    */
    public function getTovarValueProperties(array $catalog_properties,int $catalog_node_id)
    {
        $rsv=$this->connection->Execute("
            select * from catalog_tovar_properties 
                where 
                    catalog_properties in(".implode(",",$catalog_properties).") and
                    catalog_tovar in (select catalog_tovar from catalog_category2tovar where catalog_category={$catalog_node_id})
                        group by value");

        $properties_v=$rsv->fetchEntityAll();
        return $properties_v;
    }
    
}
