<?php
/*
сервис работы с ценами товара

*/
namespace Mf\Catalog\Service;
use ADO\Service\RecordSet;
use ADO\Service\Command;


use Exception;

    
class Price
{
	public $connection;
	public $cache;
    
    /**содержимое таблицы catalog_currency
    * ключ это имя валюты, например, RUB
    */
    protected $currencys=[];

    /**содержимое таблицы catalog_price_type
    * ключ это ID записи
    */
    protected $prices=[];


    public function __construct($connection,$cache)
    {
        $this->connection=$connection;
        $this->cache=$cache;
        $this->init();
    }


    /**
    * инициализация прайсов
    * загружаем из таблиц все что имеется и кешируем
    */
    protected function init()
    {
        $key="price_currency_def";
        //пытаемся считать из кеша
        $result = false;
        $rez= $this->cache->getItem($key, $result);
        if (!$result || true) {
            //варианты валюты каталога
            $rs=$this->connection->Execute("select * from catalog_currency");
            while (!$rs->EOF){
                $rez["currencys"][$rs->Fields->Item["currency"]->Value]=[
                    "amount_cnt"=>$rs->Fields->Item["amount_cnt"]->Value,
                    "amount"=>$rs->Fields->Item["amount"]->Value,
                    "base"=>$rs->Fields->Item["base"]->Value,
                    "work"=>$rs->Fields->Item["base"]->Value,
                    "current_base_rate"=>$rs->Fields->Item["current_base_rate"]->Value,
                    "numcode"=>$rs->Fields->Item["numcode"]->Value,
                    ];
                $rs->MoveNext();
            }
            $rs->Close();
            $rs=null;
            //варианты типов цен
            $rs=$this->connection->Execute("select * from catalog_price_type");
            while (!$rs->EOF){
                $rez["prices"][$rs->Fields->Item["id"]->Value]=[
                    "name"=>$rs->Fields->Item["name"]->Value,
                    "is_base"=>$rs->Fields->Item["is_base"]->Value,
                    "work"=>$rs->Fields->Item["is_base"]->Value,
                    "base"=>$rs->Fields->Item["is_base"]->Value,
                    ];
                $rs->MoveNext();
            }

            //сохраним в кеш
            $this->cache->setItem($key, $rez);
            $this->cache->setTags($key,["catalog_currency","catalog_price_type"]);
        }
        $this->currencys=$rez["currencys"];
        $this->prices=$rez["prices"];
    }
    
    
    /**
    * установить валюту в качестве рабочей для всего каталога
    * она будет использоваться по умолчанию во всех вызовах взамен дефолтной,
    * указанной в таблице валют
    * $name - строка имени валюты, достепны варианты которые имеются в таблице catalog_currency,
    * например, EUR,
    * ничего не возвращает
    * если не найдено - исключение
    */
    public function setCurrencyName(string $name)
    {
        $flag=false;
        foreach ($this->currencys as $k=>$n){
            if ($k==$name){
                $this->currencys[$k]["work"]=1;
                $flag=true;
            } else {
                $this->currencys[$k]["work"]=0;
            }
        }
        if (!$flag){
            throw new  Exception("Недопустимое имя валюты: {$name}");
        }
    }
    
    /**
    * получить имя валюты каталога, если не устанавливали раннее при помощи метода setCurrencyName()
    * возвращается дефолтная, указанна в таблице catalog_currency
    * если нигде не указана валюта умолчания - исключение
    */
    public function getCurrencyName()
    {
        foreach ($this->currencys as $k=>$c){
            if ($c["work"]==1){
                return $k;
            }
        }
        throw new  Exception("Ни одна из валют не выбрана как базовая!");
    }
    
    
    /**
    * проверить/получить ID типа прайса
    * $catalog_price_type_id - ID типа прайса, если 0, возвращает дефолтный из таблицы catalog_price_type
    * возвращает целое число ID
    * если не верный ID - исключение
    */
    public function getCheckPrice(int $catalog_price_type_id=0)
    {
        if (empty($catalog_price_type_id)){
            foreach ($this->prices as $id=>$price){
                if ($price["work"] >0){
                    return $id;
                }
            }
        }
        if (array_key_exists ($catalog_price_type_id,$this->prices)){
            return $id;
        }
        throw new  Exception("Не верный ID:{$catalog_price_type_id} типа прайса");
    }
    
    /**
    * получить мин и макс значения цен для указанного узла каталога
    * $catalog_node_id - ID узла каталога
    * $catalog_price_type_id - ID типа прайса, если 0, тогда берется прайс по умолчанию
    * $currency - тип валюты
    */
    public function getMinMaxPrice(int $catalog_node_id, int $catalog_price_type_id=0, string $currency="")
    {
        if (empty($currency)){
            $currency=$this->getCurrencyName();
        }
        
        //получим ID типа прайса
        $catalog_price_type_id=$this->getCheckPrice($catalog_price_type_id);
        
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
                ctc.catalog_price_type={$catalog_price_type_id}");
        $properties=$rs->fetchEntity();
        return $properties;
    }

}
