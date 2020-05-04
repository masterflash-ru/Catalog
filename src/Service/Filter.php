<?php
/*
сервис работы с фильтром товара

*/
namespace Mf\Catalog\Service;
use ADO\Service\RecordSet;
use ADO\Service\Command;

use Laminas\Form\Element;
use Laminas\Form\Form;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Input;

use Exception;

/*пользовательские поля*/
use Mf\Catalog\Form\Element\MoneyRange;
    
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
        
        /** первый контроли это выбор цены - ползунки* /
        $form->add(array(
            'name' => 'price',
            'type' => MoneyRange::class,
            'options' => [
                'label' => 'Цена',
            ],
        ));

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
            $widget=strtolower($pr->getWidget());
            switch ($widget){
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
                case "moneyrange":{
                    $el=new MoneyRange("f_".$pr->getId());
                    //получим из диапазона товара мин и макс цену
                    $price=$this->getMinMaxPrice($catalog_node_id,0,"RUB");
                    $el->setMin($price->getPrice_min());
                    $el->setMax($price->getPrice_max());
                    
                    break;
                }
            }
            
            $nameInput = new Input("f_".$pr->getId());
            $nameInput->setRequired(false);
            $el->setLabel($pr->getName());
            $arr=[];
            if (!empty($properties_values[$pr->getId()]) ){
                foreach ($properties_values[$pr->getId()] as $v){
                    $arr[$v->getValue()]=$v->getValue()." (".$v->getTovar_count().")";
                }
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
        $rs=$this->connection->Execute("
        select id, name, widget, sysname, poz from 
                (select 
                    cp.id, cp.name, cp.widget, cp.sysname, cp.poz
                        from  catalog_category2tovar c2t,catalog_tovar_properties ctp, catalog_properties cp
                            where 
                                cp.id=ctp.catalog_properties and
                                ctp.catalog_tovar=c2t.catalog_tovar and
                                c2t.catalog_category={$catalog_node_id} and 
                                cp.public>0 and 
                                ctp.value>''
                                group by cp.id
                                ) as widget_table
                union (select id, name, widget, sysname,poz from catalog_properties where 
                    sysname='MONEYRANGE' and public >0)
                
                order by poz");
        $properties=$rs->fetchEntityAll();
        return $properties;
    }
    
    /**
    * получить значения для наполнения виджетов фильтра
    * $catalog_properties - массив ID параметров (из таблицы catalog_properties)
    * $catalog_node_id - ID узла каталога 
    * возвращает массив универсальных объектов с заполненными св-вами из таблицы catalog_tovar_properties
    * так же возвращается кол-во товара для данного параметра и категории (публикация товара игнорируется!)
    */
    public function getTovarValueProperties(array $catalog_properties,int $catalog_node_id)
    {
        $rsv=$this->connection->Execute("
        select ctp.*, count(ctp1.catalog_tovar) as tovar_count from 
            catalog_tovar_properties ctp, catalog_category2tovar c2t
            left join catalog_tovar_properties ctp1 on ctp1.catalog_tovar=c2t.catalog_tovar

            where 
        ctp1.catalog_properties=ctp.catalog_properties and
        ctp.catalog_properties in(".implode(",",$catalog_properties).") and
        ctp.catalog_tovar=c2t.catalog_tovar and
        c2t.catalog_category={$catalog_node_id}
        group by value
            ");

        $properties_v=$rsv->fetchEntityAll();
        return $properties_v;
    }

    /**
    * получить мин и макс значения цен для указанного узла каталога
    * $catalog_node_id - ID узла каталога
    * $catalog_price_type_id - ID типа прайса, если 0, тогда берется прайс по умолчанию
    * $currency - тип валюты
    */
    public function getMinMaxPrice(int $catalog_node_id, int $catalog_price_type_id=0, string $currency="RUB")
    {
        $rs=$this->connection->Execute("
        select 
            min(ctc.value) as price_min,
            max(ctc.value) as price_max
             from catalog_category2tovar c2t
              left join catalog_tovar_currency ctc
               on ctc.catalog_tovar=c2t.catalog_tovar
               where 
                c2t.catalog_category={$catalog_node_id} and
                ctc.catalog_currency='{$currency}' and 
                ctc.value >0 and
                ((ctc.catalog_price_type={$catalog_price_type_id} and 0!={$catalog_price_type_id}) or 
                    (ctc.catalog_price_type in(select id from catalog_price_type where is_base>0 ) and 0={$catalog_price_type_id})
                )");
        $properties=$rs->fetchEntity();
        return $properties;
    }

}
