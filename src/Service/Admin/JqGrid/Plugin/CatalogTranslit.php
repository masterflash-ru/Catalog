<?php

namespace Mf\Catalog\Service\Admin\JqGrid\Plugin;

use Admin\Service\JqGrid\Plugin\AbstractPlugin;

class CatalogTranslit extends AbstractPlugin
{

    protected $connection;
    protected $def_options =[
        "source"=>"name"
    ];
    
    public function __construct($connection)
    {
        $this->connection=$connection;
    }

public function add($value,$postParameters)
{
    return $this->edit($value,$postParameters);
}
public function edit($value,$postParameters)
{
    $value=trim($value);

    if (empty($value)) {
        $id=(int)$postParameters["id"];
        if (empty($id)){
            $id=(int)$postParameters["subid"];
            //считываем все предыдущий url родительсткого раздела и строим путь
            //для новой записи родителя получаем из subid, т.к. новая запись еще не записана в базу
            $rs=$this->connection->Execute("select id,url from catalog_category where id=$id");
        } else {
            //считываем все предыдущий url родительсткого раздела и строим путь
            $rs=$this->connection->Execute("select id,url from catalog_category where id=(select subid from catalog_category where id=$id)");
        }
        $prefix=$rs->Fields->Item["url"]->Value;
        if (empty($prefix)) {$prefix=$rs->Fields->Item["id"]->Value."-";}

        $value=preg_replace('/[^0-9a-zA-Z_а-яА-Я\- ]/iu', '',trim($postParameters[$this->options["source"]]));
        $value=$this->translit($value);//переводим
        $value=preg_replace('/-{2,}/','-',$prefix.$value);
    }

    

    return $value;    
}



protected function translit($string)
{
	 $ru =implode('%', 
		array(
		'А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е', 'Ё', 'ё', 'Ж', 'ж', 'З', 'з',
		'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р',
		'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш', 'Щ', 'щ',
		'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я','!','@','#','$','&','*',' ','»','«',"'",'"','?','.',":",";",",","–",
		")","(","[","]"
			)
			);
	$ru=explode ('%',mb_convert_encoding ($ru,'windows-1251','utf-8'));

	 $en = 
	 array(
		'A', 'a', 'B', 'b', 'V', 'v', 'G', 'g', 'D', 'd', 'E', 'e', 'E', 'e', 'Zh', 'zh', 'Z', 'z', 
		'I', 'i', 'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'R', 'r',
		'S', 's', 'T', 't', 'U', 'u', 'F', 'f', 'H', 'h', 'C', 'c', 'Ch', 'ch', 'Sh', 'sh', 'Sch', 'sch',
		'_', '_', 'Y', 'y',  '', '', 'E', 'e', 'Ju', 'ju', 'Ja', 'ja','','','','','','','-','','',"","","","","","","","-",
		"","","",""
	);
	
	$string=mb_convert_encoding ($string,'windows-1251','utf-8');
	
	
	$string = str_replace($ru, $en, $string);	
	return $string;
}

}