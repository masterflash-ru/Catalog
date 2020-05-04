<?php


namespace Mf\Catalog\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper\FormInput;

class MoneyRange extends FormInput
{
    /**
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (! $element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
    * формирование HTML самого элемента
     * @return string
     */
    public function render(ElementInterface $element)
    {
        
        //клонируем, что бы не заморачиваться с созданием самих элментов
        $el=clone $element;
        $min=(int)$element->getMin();
        $max=(int)$element->getMax();
        $range=$max-$min;
        $step=ceil($range/100);
        if ($step<1){
            $step=1;
        }
        /*на всякий случай проверим верность*/
        $value=explode(",",$element->getValue());
        if (count($value)!=2){
            $value[0]=$min;
            $value[1]=$max;
        }
        if ($value[0]<$min){
            $value[0]=$min;
        }
        if ($value[1]>$max){
            $value[1]=$max;
        }
        
        
        //добавим обязательные атрибуты
        $element->setAttribute("data-provide", "slider");
        $element->setAttribute("data-slider-min", $min);
        $element->setAttribute("data-slider-max", $max);
        $element->setAttribute("data-slider-step", $step);
        $element->setAttribute("data-slider-value", "[{$value[0]},{$value[1]}]");
        $element->setAttribute('data-slider-tooltip', "hide");
        
        //добавим в начале 2 тектовых поля, где будет отображаться диапазон
        $name=$el->getName();
        $el->setValue($value[0]);
        $el->setAttribute("class", "form-control range-display");
        $el->setAttribute("data-min", $min);
        $el->setAttribute("data-max", $max);
        
        //оборачиваем 2 поля, для вывода в строку
        $start_texts='<div class="form-row money_range_container">';
        $el->setName($name."s");
        $el->setAttribute("id",$name."s");

        $start_texts.='<div class="col">'.parent::render($el).'</div>';
        $el->setValue($value[1]);
        $el->setName($name."e");
        $el->setAttribute("id",$name."e");
        $start_texts.='<div class="col">'.parent::render($el).'</div>';
        
        $start_texts.='</div>';
        //сам элемент с бегунком обернем для легкого доступа из JS
        return $start_texts.'<div class="range-element">'.parent::render($element)."</div>";
    }


    /**
     */
    protected function getType(ElementInterface $element)
    {
        return "text";
    }
}
