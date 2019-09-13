<?php
namespace Mf\Catalog\Service;

use Exception;
use ADO\Service\RecordSet;

class Import
{
    protected $connection;
    protected $ImagesLib;
    protected $config;
    
    
    public function __construct($connection,$ImagesLib,$config) 
    {
        $this->connection=$connection;
        $this->ImagesLib=$ImagesLib;
        $this->config=$config;
    }
    
    /**
    * вызывается после ипорта раздела Import из 1С
    */
	public function CatalogImport()
    {
        
        //загружаем новые категории товара, статус 1
        $a=0;
        $this->connection->BeginTrans();
        //внавале удалим все старые записи из каталога, которые совпадают с импортом и статус =1
        $this->connection->Execute("delete from catalog_category where id in(select id from import_1c_category where flag_change=1)",$a,adExecuteNoRecords);
        $this->connection->Execute("insert into catalog_category (id,subid,level,xml_id,name,url,title,keywords,description,public)
                select id,subid,level,id1c,name,url,name,name,name,1 from import_1c_category where flag_change=1",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        /*сбросим флаг новой записи*/
        $this->connection->Execute("update import_1c_category set flag_change=0 where flag_change=1",$a,adExecuteNoRecords);
        
        $this->connection->BeginTrans();
        //изменение категории, статус=2
        $this->connection->Execute("update catalog_category c,import_1c_category i
                set c.name=i.name, c.url=i.url, c.title=i.name, c.keywords=i.name, c.description=i.name
                    where i.flag_change=2 and c.id=i.id and c.subid=i.subid and c.level=i.level",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        /*сбросим флаг новой записи*/
        $this->connection->Execute("update import_1c_category set flag_change=0 where flag_change=2",$a,adExecuteNoRecords);
        
        
        $this->connection->BeginTrans();
        /*загружаем/меняем ТОВАР, в таблице import_1c_tovar всегда обновленный товар*/        
        //вначале переименовываем то что есть
        $this->connection->Execute("update catalog_tovar c, import_1c_tovar i
                set c.name=i.name, c.url=i.url, c.quantity=i.quantity,c.sku=i.sku, c.anons=i.name
                    where c.xml_id=i.id1c and (i.status is null or !i.status )",$a,adExecuteNoRecords);
        //удалим старые связи товар-категории
        $this->connection->Execute("delete from catalog_category2tovar
                    where catalog_tovar in(select id from catalog_tovar 
                                where xml_id in(select id1c from import_1c_tovar))",$a,adExecuteNoRecords);
        
        //Новая привязка к категориям будет после добавления новых товаров
        
        //загружаем новый товар
        $this->connection->Execute("insert into catalog_tovar (xml_id,name,url,info,title,keywords,description,public,poz,quantity,sku,anons)
                    select id1c,name,url,description,name,name,name,1,0,0,sku,name
                        from import_1c_tovar where (status is null or !status) and id1c not in(select xml_id from catalog_tovar where xml_id>'')",$a,adExecuteNoRecords);
        //привязываем новый товар к категориям
        $this->connection->Execute("insert into catalog_category2tovar (catalog_category, catalog_tovar)
                select ic.id, t.id
                        from import_1c_category ic,import_1c_tovar it, catalog_tovar t
                            where it.category_id1c=ic.id1c and t.xml_id=it.id1c and t.xml_id>''
                    ",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();

        $this->connection->BeginTrans();
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
                    concat('s:',length(value),':\"',value,'\";')
                        from import_1c_tovar_properties
                            where property_list_id not in(select xml_id from catalog_properties where xml_id>'')
                    ",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        $this->connection->BeginTrans();
        //загрузка единиц измерения и коэффициенты
        //вначале переименование
        $this->connection->Execute("update catalog_tovar_gabarits g, import_1c_tovar i, catalog_tovar c
                set g.catalog_measure_code=i.measure, g.coefficient=i.measure_ratio
                    where g.catalog_tovar and c.xml_id=i.id1c",$a,adExecuteNoRecords);
        //добавляем новые
        $this->connection->Execute("insert into catalog_tovar_gabarits (catalog_measure_code,coefficient,catalog_tovar)
                select measure,measure_ratio,
                    (select id from catalog_tovar where xml_id=import_1c_tovar.id1c)
                    from import_1c_tovar
                        where id1c in(select xml_id from catalog_tovar where id not in(select catalog_tovar from catalog_tovar_gabarits))",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        $this->connection->BeginTrans();
        //бренды загружаем в общие параметры товара с именем "BREND"
        $this->connection->Execute("insert into catalog_properties_list (xml_id,value,catalog_properties)
                select id1c,name,
                    (select id from catalog_properties where sysname='BREND')
                        from import_1c_brend
                            where id1c 
                                not in(select xml_id from catalog_properties_list 
                                        where xml_id>'' and catalog_properties in(select id from catalog_properties where sysname='BREND')
                                        ) group by id1c
                    ",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        $this->connection->BeginTrans();
        //смотрим удаление товара, удалим при статусе "Удален"
        $this->connection->Execute("delete from catalog_tovar 
                                    where xml_id in(select id1c from import_1c_tovar where status='Удален')",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        //при удалении 1С передает и файлы к этому товару, их нужно удалить
        $rs=new RecordSet();
        $rs->Open("select * from import_1c_file where import_1c_tovar in(select id1c from import_1c_tovar where status='Удален')",$this->connection);
        while (!$rs->EOF){
            @unlink ($rs->Fields->Item["file"]->Value);
            $rs->MoveNext();
        }
        $this->deleteEmptyDir();


    }

    /**
    * вызывается после ипорта раздела Offers из 1С
    */
	public function CatalogOffers()
    {
        $this->connection->BeginTrans();
        //загружаем новый тип цен
        $this->connection->Execute("insert into catalog_price_type (xml_id,name,is_base)
                    select id1c,type,0
                        from import_1c_price_type where flag_change=1 and id1c not in(select xml_id from catalog_price_type where xml_id>'')",$a,adExecuteNoRecords);
        
        //смотрим кол-во типов цен, если запись единственная, тогда ее делаем по умолчанию, базовой
        $rs=$this->connection->Execute("select count(*) as c from catalog_price_type");
        if ($rs->Fields->Item["c"]->Value==1 && !$rs->EOF){
            $this->connection->Execute("update catalog_price_type set is_base=1",$a,adExecuteNoRecords);
        }
        
        
        //меняем имя, если было изменение
        $this->connection->Execute("update catalog_price_type c, import_1c_price_type i
                    set c.name=i.type where c.xml_id=i.id1c",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        $this->connection->BeginTrans();
        //типы склада
        $this->connection->Execute("insert into catalog_store (xml_id,name,public)
                    select id1c,type,1
                        from import_1c_store_type where flag_change=1 and id1c not in(select xml_id from catalog_store where xml_id>'')",$a,adExecuteNoRecords);
        //меняем имя, если было изменение
        $this->connection->Execute("update catalog_store c, import_1c_store_type i
                    set c.name=i.type where c.xml_id=i.id1c",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        $this->connection->BeginTrans();
        //загружаем все цены
        $this->connection->Execute("insert catalog_tovar_currency (catalog_tovar,catalog_currency,catalog_price_type,value,vat_in,vat_value)
                    select 
                        (select id from catalog_tovar where xml_id=import_1c_price.id1c),
                        currency,
                        (select id from catalog_price_type where xml_id=import_1c_price.import_1c_price_type),
                        price,
                        (select vat_in from import_1c_price_type where id1c=import_1c_price.import_1c_price_type),
                        (select vat from import_1c_tovar where id1c=import_1c_price.id1c)
                            from 
                                import_1c_price where 
                                    id1c not in(select xml_id from catalog_tovar where xml_id>'' and id in(select catalog_tovar from catalog_tovar_currency))",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        $this->connection->BeginTrans();
        //грузим остатки
        $this->connection->Execute("insert catalog_tovar_store (catalog_store,catalog_tovar,quantity)
                    select 
                        (select id from catalog_store where xml_id=import_1c_store.import_1c_store_type),
                        (select id from catalog_tovar where xml_id=import_1c_store.id1c),
                        quantity
                            from 
                                import_1c_store where 
                                    id1c not in(select t.xml_id from catalog_tovar t, catalog_tovar_store s
                                        where xml_id>'' and t.id=s.catalog_tovar)
                                        and id1c in(select xml_id from catalog_tovar)",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        $this->connection->BeginTrans();
        //обновим общий остаток товара
        $this->connection->Execute("update catalog_tovar c , import_1c_tovar i
                    set c.quantity=i.quantity
                        where i.id1c=c.xml_id",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();

        

 /* 


*/

    }
    
    /**
    * вызывается перед импортом, если у нас полная замена каталога
    * возвращает результат, который передается как есть на экран
    */
    public function CatalogTruncate()
    {
        $this->connection->BeginTrans();
        //удалим все свойства товара где указан xml_id (в связных таблицах очистится автоматом, через внешние ключи)
        $this->connection->Execute("delete from catalog_properties where xml_id>''",$a,adExecuteNoRecords);
        //очистим сам каталог товара
        $this->connection->Execute("delete from catalog_tovar where xml_id>''",$a,adExecuteNoRecords);
        $this->connection->CommitTrans();
        
        //удалим все фото из каталога, которые отмечены после удаления из catalog_tovar
        $this->ImagesLib->clearStorage();
        $a=0;
        $this->connection->Execute("ALTER TABLE catalog_tovar AUTO_INCREMENT=1",$a,adExecuteNoRecords);
        $this->connection->Execute("ALTER TABLE catalog_tovar_currency AUTO_INCREMENT=1",$a,adExecuteNoRecords);
        $this->connection->Execute("ALTER TABLE catalog_tovar_gallery AUTO_INCREMENT=1",$a,adExecuteNoRecords);
        $this->connection->Execute("ALTER TABLE catalog_tovar_store AUTO_INCREMENT=1",$a,adExecuteNoRecords);
    }
    
    /**
    * обработчик дополнительных реквизитов товара
    * обрабатывает тип "ОписаниеВФорматеHTML"
    * если он есть, данные записываются в подробное описание товара в поле info таблицы catalog_tovar
    */
    public function CatalogRequisites()
    {
        $a=0;
        $this->connection->Execute("update catalog_tovar c, import_1c_requisites i
                        set c.info=i.value
                            where c.xml_id=i.import_1c_tovar and i.name='ОписаниеВФорматеHTML'",$a,adExecuteNoRecords);
    }
    
    
    /**
    * обработка по расписанию картинок
    */
    public function cron()
    {
        set_time_limit(0);
        $rez=[];
        $rs=$this->connection->Execute("SELECT AUTO_INCREMENT  FROM information_schema.tables
                                                WHERE
                                                  table_name = 'catalog_tovar_gallery'
                                                  AND table_schema = DATABASE()");
        $catalog_tovar_gallery_id=$rs->Fields->Item['AUTO_INCREMENT']->Value;
        $rs->Close();
        $rs=null;
        $rsg=new RecordSet();
        $rsg->CursorType =adOpenKeyset;
        $rsg->Open("select * from catalog_tovar_gallery limit 1",$this->connection);

        $limit=(int)$this->config["catalog"]["import"]["limit_record_read_attach_files"];
        $folder=$this->ImagesLib->getSourceFolder();
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open("select * from import_1c_file order by import_1c_tovar limit $limit",$this->connection);
        //весь товар
        $rst=new RecordSet();
        $rst->CursorType =adOpenKeyset;
        $rst->Open("select * from catalog_tovar order by xml_id",$this->connection);
        
        while (!$rs->EOF){
            $rst->Find("xml_id='".$rs->Fields->Item["import_1c_tovar"]->Value."'");
            $catalog_tovar=$rst->Fields->Item["id"]->Value;
            $rez[]=$rst->Fields->Item["id"]->Value;

            $flag_def_img=$this->ImagesLib->hasImage("catalog_tovar_anons",$catalog_tovar) ;
            $f=$rs->Fields->Item["file"]->Value;
            $file_ext=strtolower(pathinfo($f,PATHINFO_EXTENSION));
            $file_name=basename($f);

            //проверим на возможную ошибку
            if (!is_readable($f)){
                $rs->Delete();
                $rs->Update();
                $rs->MoveNext();
                @unlink($f);
                continue;
            }
           
            if (in_array($file_ext,["jpg","png","jpeg","gif"])){
                //обработка картинок
                if (!$flag_def_img){
                     copy($f,$folder."/".$file_name);
                    //анонс товара
                    $this->ImagesLib->selectStorageItem("catalog_tovar_anons");
                    $this->ImagesLib->saveImages($file_name,"catalog_tovar_anons",$catalog_tovar);
                    //подробно товар
                     copy($f,$folder."/".$file_name);
                    $this->ImagesLib->selectStorageItem("catalog_tovar_detal");
                    $this->ImagesLib->saveImages($file_name,"catalog_tovar_detal",$catalog_tovar);
                    $flag_def_img=false;
                } else {
                     copy($f,$folder."/".$file_name);
                    //остальные фото отправляем в фотогалерею
                    $rsg->AddNew();
                    $rsg->Fields->Item["catalog_tovar"]->Value=$catalog_tovar;
                    $rsg->Update();
                    $this->ImagesLib->selectStorageItem("catalog_tovar_gallery");
                    $this->ImagesLib->saveImages($file_name,"catalog_tovar_gallery",$rsg->Fields->Item["id"]->Value);
                }
            }
            
            //костыльный обработчик, длинные описания товара хранятся в BMP файлах
            if (in_array($file_ext,["bmp"])){
                $c=file_get_contents($f);
                $c=mb_convert_encoding($c,"UTF-8","CP1251");
                $c=nl2br(trim(str_replace("\r","",$c)));
                $rst->Fields->Item["info"]->Value=$c;
                $rst->Update();
            }
            @unlink($f);
            $rs->Delete();
            $rs->Update();
            $rs->MoveNext();
        }
        $this->deleteEmptyDir();
        return $rez;
    }

/*
* обход каталогов с исходными файлами, удаление пустых папок
*/
protected function deleteEmptyDir()
{
    $folder=$this->config["1c"]["temp1c"];
        try {
            $idir = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $folder, \FilesystemIterator::SKIP_DOTS ), \RecursiveIteratorIterator::CHILD_FIRST );
        }
        catch (\UnexpectedValueException $e) { return;}
        
        foreach( $idir as $v ){
            if( $v->isDir() and $v->isWritable() ){
                $f = glob( $idir->key() . '/*.*' );
                if( empty( $f ) ){
                    @rmdir( $idir->key() );
                }
            }
        } 
}


}
