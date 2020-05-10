<?php
/*
* помощник view для вывода цены товара
*/

namespace Mf\Catalog\View\Helper;


use Laminas\View\Helper\AbstractHelper;
//use ADO\Service\RecordSet;
use Exception;

/**
 * помощник - вывода фильтра товара
 */
class TovarCurrency extends AbstractHelper 
{
    protected $connection;
    protected $cache;
    protected $_default=[
    ];


/*конструктор
*/
public function __construct ($cache,$connection)
  {
    $this->connection=$connection;
    $this->cache=$cache;
  }

/**
* 
*/
public function __invoke()
{

    return "Цена:";
}

}