<?php
namespace Mf\Catalog\Service;

use Exception;
use ADO\Service\RecordSet;

class Import 
{
    protected $connection;
    
    
    public function __construct($connection) 
    {
        $this->connection=$connection;
    }
    
    /**
    * вызывается после ипорта раздела Import из 1С
    */
	public function CatalogImport()
    {
        
        //загружаем новые категории товара, статус 1
        $a=0;
        //внавале удалим все старые записи из каталога, которые совпадают с импортом и статус =1
        $this->connection->Execute("delete from catalog_category where id in(select id from import_1c_category where flag_change=1)",$a,adExecuteNoRecords);
        $this->connection->Execute("insert into catalog_category (id,subid,level,xml_id,name,url,title,keywords,description,public)
                select id,subid,level,id1c,name,url,name,name,name,1 from import_1c_category where flag_change=1",$a,adExecuteNoRecords);
        /*сбросим флаг новой записи*/
        $this->connection->Execute("update import_1c_category set flag_change=0 where flag_change=1",$a,adExecuteNoRecords);
        
        //изменение категории, статус=2
        $this->connection->Execute("update catalog_category c,import_1c_category i
                set c.name=i.name, c.url=i.url, c.title=i.name, c.keywords=i.name, c.description=i.name
                    where i.flag_change=2 and c.id=i.id and c.subid=i.subid and c.level=i.level",$a,adExecuteNoRecords);
        /*сбросим флаг новой записи*/
        $this->connection->Execute("update import_1c_category set flag_change=0 where flag_change=2",$a,adExecuteNoRecords);
        
        /*загружаем/меняем ТОВАР, в таблице import_1c_tovar всегда обновленный товар*/        
        //вначале переименовываем то что есть
        $this->connection->Execute("update catalog_tovar c, import_1c_tovar i
                set c.name=i.name, c.url=i.url
                    where c.xml_id=i.id1c",$a,adExecuteNoRecords);
        //удалим старые связи товар-категории
        $this->connection->Execute("delete from catalog_category2tovar
                    where catalog_tovar in(select id from catalog_tovar 
                                where xml_id in(select id1c from import_1c_tovar))",$a,adExecuteNoRecords);
        
        //Новая привязка к категориям будет после добавления новых товаров
        
        //загружаем новый товар
        $this->connection->Execute("insert into catalog_tovar (xml_id,name,url,info,title,keywords,description,public,poz)
                    select id1c,name,url,description,name,name,name,1,0
                        from import_1c_tovar where id1c not in(select xml_id from catalog_tovar where xml_id>'')",$a,adExecuteNoRecords);
        //привязываем новый товар к категориям
        $this->connection->Execute("insert into catalog_category2tovar (catalog_category, catalog_tovar)
                select ic.id, t.id
                        from import_1c_category ic,import_1c_tovar it, catalog_tovar t
                            where it.category_id1c=ic.id1c and t.xml_id=it.id1c and t.xml_id>''
                    ",$a,adExecuteNoRecords);

        /*работаем со СВОЙСТВАМИ всегда обновление, при полной замене каталога все очищается прежде*/
        //возможное переименование 
        $this->connection->Execute("update catalog_properties c, import_1c_properties i
                            set c.name=i.name, c.type=(case i.type when 'voc' then 'select'
                    		when 'str' then 'text'
                            when 'int' then 'text'
                             else 'text' end)
                                where c.xml_id=i.id1c",$a,adExecuteNoRecords);
        //доабвим новые, если их не было
        $this->connection->Execute("insert into catalog_properties (name,xml_id,type)
                select name,id1c,(case type when 'voc' then 'select'
                    		when 'str' then 'text'
                            when 'int' then 'text'
                             else 'text' end)
                        from import_1c_properties
                            where id1c not in(select xml_id from catalog_properties where xml_id>'') group by id1c
                    ",$a,adExecuteNoRecords);
        //варианты для списков
        $this->connection->Execute("insert into catalog_properties_list (xml_id,value,catalog_properties)
                select id1c,value,
                    (select id from catalog_properties where xml_id=import_1c_properties_list.import_1c_properties)
                        from import_1c_properties_list
                            where id1c not in(select xml_id from catalog_properties_list where xml_id>'') group by id1c
                    ",$a,adExecuteNoRecords);
        //значения параметров
        $this->connection->Execute("insert into catalog_tovar_properties (catalog_tovar,catalog_properties_list,catalog_properties,value)
                select 
                    (select id from catalog_tovar where xml_id=import_1c_tovar_properties.1c_tovar_id1c  and xml_id>''),
                    (select id from catalog_properties_list where xml_id=import_1c_tovar_properties.property_list_id and xml_id>''),
                    (select id from catalog_properties where xml_id=import_1c_tovar_properties.property_id  and xml_id>''),
                    concat('s:',length(value),':"',value,'";')
                        from import_1c_tovar_properties
                            where property_list_id not in(select xml_id from catalog_properties where xml_id>'')
                    ",$a,adExecuteNoRecords);

        

    }

    /**
    * вызывается после ипорта раздела Offers из 1С
    */
	public function CatalogOffers()
    {

    }
    
    /**
    * вызывается перед импортом, если у нас полная замена каталога
    */
    public function CatalogTruncate()
    {
        //удалим все свойства товара где указан xml_id (в связных таблицах очистится автоматом, через внешние ключи)
        $this->connection->Execute("delete from catalog_properties where xml_id>''",$a,adExecuteNoRecords);
    }
    
    
}
