<?php

namespace Mf\Catalog\Form\Element;

use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\InputFilter\InputProviderInterface;


class MoneyRange extends Element implements InputProviderInterface
{
    protected $attributes = [
        'type' => 'MoneyRange',
    ];
    
    /**минимальное*/
    protected $minv=0;
    
    /**максимальное*/
    protected $maxv=0;
    
    
    
    /**
    * установить минимум для диапазона
    */
    public function setMin($val)
    {
        $this->minv=ceil($val);
    }

    /**
    * установить максимум для диапазона
    */
    public function setMax($val)
    {
        $this->maxv=ceil($val);
    }

    /**
    * получить минимум для диапазона
    */
    public function getMin()
    {
        return $this->minv;
    }

    /**
    * получить максимум для диапазона
    */
    public function getMax()
    {
        return $this->maxv;
    }
    
    
    /**
     * Спецификация элемента
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ];
    }
    
    /**
    * пустой метод, для исключения ошибки при генерации фильтра
    * во всех элементах предусматривается массив
    */
    public function setValueOptions()
    {
        
    }
    
}