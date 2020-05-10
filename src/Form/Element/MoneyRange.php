<?php

namespace Mf\Catalog\Form\Element;

use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\InputFilter\InputProviderInterface;
use Laminas\Validator\Regex as RegexValidator;
use Laminas\Validator\ValidatorInterface;

class MoneyRange extends Element implements InputProviderInterface
{
    protected $attributes = [
        'type' => 'MoneyRange',
    ];
    
    /**минимальное*/
    protected $minv=0;
    
    /**максимальное*/
    protected $maxv=0;
    
    protected $validator;
    
    public function getValidator()
    {
        if (null === $this->validator) {
            $validator = new RegexValidator('/^\d+,\d+$/');
            $validator->setMessage(
                'Не верный тип данных, должно быть: число,число',
                RegexValidator::NOT_MATCH
            );
            $this->validator = $validator;
        }
        return $this->validator;
    }
    
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }
    
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
            'validators' => [
                $this->getValidator(),
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