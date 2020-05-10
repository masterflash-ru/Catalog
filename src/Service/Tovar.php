<?php
/*
модуль работы с товаром из каталога (список, карточка )

*/
namespace Mf\Catalog\Service;
use ADO\Service\RecordSet;
use ADO\Service\Command;


use Mf\Catalog\Entity\Properties;
use Laminas\Paginator\Adapter;
use Laminas\Paginator\Paginator;

    
class Tovar
{
    /*соединение с базой*/
	protected $connection;
    
    /*объект для кеширования*/
	protected $cache;
    
    /*объект фильтрации каталога*/
    protected $filter;
    
    /**экземпляр Price*/
    protected $price;
    
    /***/


    /**
    * $connection - соединение с базой,
    * $cache - объект кеширования,
    * $filter - экзепляр работы с фильтром товара,
    * $price - экзепляр работы с ценами товара
    */
    public function __construct($connection,$cache,$filter,$price)
    {
        $this->connection=$connection;
        $this->cache=$cache;
        $this->price=$price;
        $this->filter=$filter;
    }



    /**
    * список товара
    * $catalog_category_id - ID категории товара
    * 
    * 
    * возвращает Paginator списка товара (НЕ кешируется!)
    */
    public function GetTovarList($catalog_category_id)
    {
        //создаем ключ кеша
        $key="tovar_list_{$catalog_category_id}";
        //пытаемся считать из кеша
        $result = false;
        $tovars= $this->cache->getItem($key, $result);
        if (!$result || true){
            //промах кеша, создаем
            //$c=new Command();
            //$c->NamedParameters=true;
            //$c->ActiveConnection=$this->connection;
            
            
            $command_sql=$this->filter->getADOCommandFromFilter($catalog_category_id);
            $command=$command_sql[0];
            
            //узел каталога
            $p=$command->CreateParameter('catalog_category', adInteger, adParamInput, 127, $catalog_category_id);
            $command->Parameters->Append($p);//добавим в коллекцию
            
            //имя валюты
            $p=$command->CreateParameter('currency_name', adChar, adParamInput, 127, $this->price->getCurrencyName());
            $command->Parameters->Append($p);//добавим в коллекцию
            
            //ID прайса
            $p=$command->CreateParameter('price_type', adInteger, adParamInput, 127, $this->price->getCheckPrice());
            $command->Parameters->Append($p);//добавим в коллекцию

            
            $command->CommandText="select 
            t.*,
            tcur.value as tovar_currency,
            tcur.catalog_currency currency_name,
            tcur.catalog_price_type price_type_id,
                (select value from 
                            catalog_tovar_properties 
                                where catalog_properties in(select id from catalog_properties where sysname='PARENT_SKU') and catalog_tovar=t.id) psku
                     from catalog_tovar t,catalog_category2tovar c2t,catalog_category c, catalog_tovar_currency tcur
                        where 
                            t.id=c2t.catalog_tovar and 
                            c2t.catalog_category=c.id and
                            c2t.catalog_category=:catalog_category  and 
                            c.public>0 and
                            t.public>0 and
                            /*что касается прайсов*/
                                tcur.catalog_tovar=t.id and 
                                tcur.catalog_currency=:currency_name and 
                                tcur.catalog_price_type=:price_type
                            /*другие фильтры*/
                            ".implode("\n",$command_sql[1])."
                            order by t.name
                            ";//\Admin\Debug::dump($command->CommandText);
            $rs=new RecordSet();
            $rs->CursorType =adOpenKeyset;
            $rs->Open($command);
            //if ($rs->EOF) {throw new  \Exception("Запись в не найдена");}
            $items=$rs->FetchEntityAll();
            $tovars = new Paginator(new Adapter\ArrayAdapter($items));

            //сохраним в кеш
            $this->cache->setItem($key, $tovars);
            $this->cache->setTags($key,["catalog_category","catalog_tovar"]);
        }
        return $tovars;

    }


    /**подробно товар
    * $category - строка URL узла категории товара
    * $url - строка URL товара 
    */
    public function GetTovarInfo($category,$url)
    {
        $key="tovar_".preg_replace('/[^0-9a-zA-Z_\-]/iu', '',$category).preg_replace('/[^0-9a-zA-Z_\-]/iu', '',$url);;
        //пытаемся считать из кеша
        $result = false;
        $rez= $this->cache->getItem($key, $result);
        if (!$result || true){
            $c=new Command();
            $c->NamedParameters=true;
            $c->ActiveConnection=$this->connection;
            $p=$c->CreateParameter('url', adChar, adParamInput, 127, $url);
            $c->Parameters->Append($p);
            $p1=$c->CreateParameter('category', adChar, adParamInput, 127, $category);
            $c->Parameters->Append($p1);
            $c->CommandText="select t.*
                     from catalog_tovar t,catalog_category2tovar c2t,catalog_category c
                        where 
                            t.id=c2t.catalog_tovar and 
                            c2t.catalog_category=c.id and
                            t.url=:url  and 
                            c.url=:category and
                            c.public>0 and
                            t.public>0";
            $rs=new RecordSet();
            $rs->CursorType = adOpenKeyset;
            $rs->Open($c);
            if ($rs->EOF) {throw new  \Exception("Товар не найден!");}
            $rez=$rs->FetchEntity();
            //сохраним в кеш
            $this->cache->setItem($key, $rez);
            $this->cache->setTags($key,["catalog_category","catalog_tovar"]);

        }
        return $rez;

    }
    /*получить путь готовый для размещения
    на входе ID текущего узла
    */
    public function GetPath($id)
    {
        //создаем ключ кеша
        $key="tovar_breadcrumb_".$id;
        //пытаемся считать из кеша
        $result = false;
        $rez= $this->cache->getItem($key, $result);
        if (!$result || true){
            $this->catalog_un_tree($id);
             unset($this->_un_tree_id[count($this->_un_tree_id)-1]);
            unset($this->_un_tree_name[count($this->_un_tree_name)-1]);
            unset($this->_un_tree_url[count($this->_un_tree_url)-1]);
            $rez= array("id"=>$this->_un_tree_id,"name"=>$this->_un_tree_name,"url"=>$this->_un_tree_url);

            //сохраним в кеш
            $this->cache->setItem($key, $rez);
            $this->cache->setTags($key,["catalog_category","catalog_tovar"]);
        }
        return $rez;

    }



    /**
    * ВРЕМЕННАЯ
    * получить все паратмеры товара, которые привязаны
    * $url - URL товара
    */
    public function getParameters($url)
    {
            $c=new Command();
            $c->NamedParameters=true;
            $c->ActiveConnection=$this->connection;
            $p=$c->CreateParameter('url', adChar, adParamInput, 127, $url);
            $c->Parameters->Append($p);
            $c->CommandText="select p.*,
                    (select name from catalog_properties where id=p.catalog_properties) as properties_name
                     from catalog_tovar t,catalog_tovar_properties p
                        where 
                            p.catalog_tovar=t.id and
                            t.url=:url  and 
                            t.public>0";
            $rs=new RecordSet();
            $rs->CursorType = adOpenKeyset;
            $rs->Open($c);
            $rez=$rs->FetchEntityAll(Properties::class);
    return $rez;
    }



    public function catalog_un_tree($id)
    {//обратный обход
    //возвращает массив с идентификаторами узлов
    //на входе id - обязательно для узла!!!!

        $t=$this->connection->Execute("select subid,name,id,url from catalog_category where id='$id' and public>0");//получить ссылку на родителя
        $this->_un_tree_url[]=$t->Fields->Item['url']->Value;
        $this->_un_tree_id[]=$t->Fields->Item['id']->Value;
        $this->_un_tree_name[]=$t->Fields->Item['name']->Value;
        if ($t->Fields->Item['subid']->Value==0) return;//если результат 0, тогда это корневой элемент, и нужно остановсться
        $this->catalog_un_tree($t->Fields->Item['subid']->Value);//рекурсивно обойти дерево к началу
    }

}
