<?php
/*
модуль работы с каталогом

*/
namespace Mf\Catalog\Service;
use ADO\Service\RecordSet;
use ADO\Service\Command;
use Mf\Catalog\Entity\Catalog as CatalogNode;
use Mf\Catalog\Entity\Tovar;
use Mf\Catalog\Entity\Properties;
use Zend\Paginator\Adapter;
use Zend\Paginator\Paginator;

    
class Catalog
{
	public $connection;
	public $cache;


public function __construct($connection,$cache)
	{
		$this->connection=$connection;
    $this->cache=$cache;
	}



/*список подуровней в выбранном узле
$id - ID текущего узла
*/
public function GetNodeList($id=0)
{
    //создаем ключ кеша
    $key="catalog_item_{$id}";
    //пытаемся считать из кеша
    $result = false;
    $nodes= $this->cache->getItem($key, $result);
    if (!$result || true){
        //промах кеша, создаем
        $c=new Command();
        $c->NamedParameters=true;
        $c->ActiveConnection=$this->connection;
        $p=$c->CreateParameter('id', adInteger, adParamInput, 127, $id);//генерируем объек параметров
        $c->Parameters->Append($p);//добавим в коллекцию
        $c->CommandText="select * from catalog_category where subid=:id and public>0";
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open($c);
        $nodes=$rs->FetchEntityAll(CatalogNode::class);
        
        //сохраним в кеш
        $this->cache->setItem($key, $nodes);
        $this->cache->setTags($key,["catalog_category"]);
    }
    return $nodes;

}


/*
получить инфу по узлу
*/
public function getNodeInfo($url)
{
    //создаем ключ кеша
    $key="catalog_".preg_replace('/[^0-9a-zA-Z_\-]/iu', '',$url);
    //пытаемся считать из кеша
    $result = false;
    $node= $this->cache->getItem($key, $result);
    if (!$result || true){
        //промах кеша, создаем
        $c=new Command();
        $c->NamedParameters=true;
        $c->ActiveConnection=$this->connection;
        $p=$c->CreateParameter('url', adChar, adParamInput, 127, $url);//генерируем объек параметров
        $c->Parameters->Append($p);//добавим в коллекцию
        $c->CommandText="select * from catalog_category where url=:url and public>0";
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open($c);
        if ($rs->EOF) {throw new  \Exception("Запись в не найдена");}
        $node=$rs->FetchEntity(CatalogNode::class);
        
        //сохраним в кеш
        $this->cache->setItem($key, $node);
        $this->cache->setTags($key,["catalog_category"]);
    }
    return $node;
}


/*список товара*/
public function GetTovarList($url)
{
    //создаем ключ кеша
    $key="tovar_list_".preg_replace('/[^0-9a-zA-Z_\-]/iu', '',$url);
    //пытаемся считать из кеша
    $result = false;
    $tovars= $this->cache->getItem($key, $result);
    if (!$result || true){
        //промах кеша, создаем
        $c=new Command();
        $c->NamedParameters=true;
        $c->ActiveConnection=$this->connection;
        $p=$c->CreateParameter('url', adChar, adParamInput, 127, $url);//генерируем объек параметров
        $c->Parameters->Append($p);//добавим в коллекцию
        $c->CommandText="select t.*,
            (select value from catalog_tovar_properties where catalog_properties in(select id from catalog_properties where sysname='PARENT_SKU') and catalog_tovar=t.id) psku
				 from catalog_tovar t,catalog_category2tovar c2t,catalog_category c
				 	where 
                        t.id=c2t.catalog_tovar and 
                        c2t.catalog_category=c.id and
                        c.url=:url  and 
                        c.public>0 and
                        t.public>0
                        order by t.name
						";
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open($c);
        //if ($rs->EOF) {throw new  \Exception("Запись в не найдена");}
        $items=$rs->FetchEntityAll(Tovar::class);
        $tovars = new Paginator(new Adapter\ArrayAdapter($items));
        
        //сохраним в кеш
        $this->cache->setItem($key, $tovars);
        $this->cache->setTags($key,["catalog_category","catalog_tovar"]);
    }
    return $tovars;
	
}


/*подробно товар*/
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
        $rez=$rs->FetchEntity(Tovar::class);
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
