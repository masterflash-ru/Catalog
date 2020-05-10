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
	protected $connection;
	protected $cache;
    
    /**экземпляр Price*/
    protected $price;
    /*флаг применения фильтра*/
    protected $do_filter=false;
    
    /**кешированая форма*/
    protected $forma;
    
    /*справочник из таблицы catalog_properties,
    * ключ массива ID параметра
    */
    protected $catalog_properties;


    public function __construct($connection,$cache,$price)
    {
        $this->connection=$connection;
        $this->cache=$cache;
        $this->price=$price;
        $this->init();
    }


    /**
    * инициализация параметров
    * загружаем из таблиц все что имеется и кешируем
    */
    protected function init()
    {
        $key="catalog_properties_def";
        //пытаемся считать из кеша
        $result = false;
        $rez= $this->cache->getItem($key, $result);
        if (!$result || true) {
            //варианты валюты каталога
            $rs=$this->connection->Execute("select * from catalog_properties where public>0");
            while (!$rs->EOF){
                $rez[$rs->Fields->Item["id"]->Value]=[
                    "name"=>$rs->Fields->Item["name"]->Value,
                    "type"=>$rs->Fields->Item["type"]->Value,
                    "sysname"=>$rs->Fields->Item["sysname"]->Value,
                    "widget"=>$rs->Fields->Item["widget"]->Value,
                    ];
                $rs->MoveNext();
            }

            //сохраним в кеш
            $this->cache->setItem($key, $rez);
            $this->cache->setTags($key,["catalog_currency","catalog_price_type","catalog_properties"]);
        }
        $this->catalog_properties=$rez;
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
    * Получить заполненный ADO\Service\Command с наполненной коллекцией параметров для выборки списка товара
    * коллекция формируется исходя из данных формы, если конечно применялся фильтр
    *
    * $catalog_category_id - ID категории товара каталога для которого делаем выборку
    */
    public function getADOCommandFromFilter($catalog_category_id)
    {
        $command=new Command();
        $command->NamedParameters=true;
        $command->ActiveConnection=$this->connection;
        
        //формируем форму фильтра и заполняем ее если есть чем, там же проверка на валидность
        $forma=$this->createForm($catalog_category_id);
        //фильтр применен?
        $sql=[];
        if ($this->isFiltered()){
            //да, формируем Command
            foreach ($forma as $k=>$el){
                $_v=$el->getValue();
                //ID из таблицы catalog_properties (ID параметра)
                $id=explode("_",$k);
                $id=$id[1];
                $properties=$this->catalog_properties[$id]; //вся ифнормация о параметре из справочника
                //в зависимости от типа параметра формируем параметры в ADO
                if (strtoupper($properties["sysname"])=="MONEYRANGE") {
                    //диапазон цен
                    $dprice=explode(",",$_v);
                    $p=$command->CreateParameter($k."s", adDouble, adParamInput, 127, $dprice[0]);
                    $command->Parameters->Append($p);
                    $p1=$command->CreateParameter($k."e", adDouble, adParamInput, 127, $dprice[1]);
                    $command->Parameters->Append($p1);
                    $sql[]="and tcur.value < :".$p1->Name." and tcur.value > :".$p->Name;
                } else {
                    if (empty($_v)){
                        //пропускаем пустые значения (т.е. не выбранные фильтры)
                        continue;
                    }
                    $p=$command->CreateParameter("id".$k, adInteger, adParamInput, 127, $id);//генерируем объек параметров
                    $command->Parameters->Append($p);
                    $s=[];//накапливаем псевдопеременные в строку запроса
                    if (is_array($_v)){
                        foreach ($_v as $index=>$el_value_item){
                            $p1=$command->CreateParameter($k.$index, adChar, adParamInput, 127, $el_value_item);//генерируем объек параметров
                            $command->Parameters->Append($p1);
                            $s[]=":".$k.$index;
                        }
                    } else {
                        $p1=$command->CreateParameter($k, adChar, adParamInput, 127, $_v);//генерируем объек параметров
                        $command->Parameters->Append($p1);
                        $s=[":".$k];
                    }
                    $s=implode(",",$s);
                    $sql[]=" and t.id in(select catalog_tovar 
                                            from catalog_tovar_properties 
                                                where catalog_properties=:".$p->Name." and value in({$s}))";
                }
            }
        }
        return [$command,$sql];
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
    * возвращает экземпляр Laminas\Form\Form с заполненными данными
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
                    $price=$this->price->getMinMaxPrice($catalog_node_id);
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


}
