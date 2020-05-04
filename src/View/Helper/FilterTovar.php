<?php
/*
* помощник view для вывода фильтра товара
*/

namespace Mf\Catalog\View\Helper;


use Laminas\View\Helper\AbstractHelper;
//use ADO\Service\RecordSet;
use Exception;
use Laminas\Form\Element;
use Laminas\Form\Form;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Input;

/**
 * помощник - вывода фильтра товара
 */
class FilterTovar extends AbstractHelper 
{
    protected $connection;
    protected $cache;
    protected $filter;
    protected $_default=[
    ];


/*конструктор
*параметры передаются из фабрики
*/
public function __construct ($filter,$cache,$connection)
  {
    $this->connection=$connection;
    $this->cache=$cache;
    $this->filter=$filter;
  }

/**
* $catalog_node_id - ID текущего узла каталога
*/
public function __invoke(int $catalog_node_id)
{

    $form=$this->filter->createForm($catalog_node_id);
    

    if ($this->filter->isFiltered()){
        \Admin\Debug::dump($form->getData());
    }
    
     $el=new Element\Submit("dofilter");
    $el->setValue("Применить фильтр");
    $form->add($el);
    
    $form->setAttribute('method', 'get');
    $form->prepare();
    $renderer=$this->getView();
    
    //добавим саму форму (теги)
    $formhelper=$renderer->form();
    $html=$formhelper->openTag($form);
    //помощник вывода всех элементов формы
    $formElement=$renderer->formElement();
    
    //добавим наш элемент вывода элмента
    $formElement->addType("MoneyRange","MoneyRange");
    $formElement->addClass(\Mf\Catalog\Form\View\Helper\MoneyRange::class,"MoneyRange");

    //рендер формы при помощи модифицированного formRow помощника
    foreach ($form as $element) {
        $html.=$renderer->FilterFormRow($element);
    }
    $html.=$formhelper->closeTag();
    
    return "NODE_ID:".$catalog_node_id." FILTER".$html;
}

}