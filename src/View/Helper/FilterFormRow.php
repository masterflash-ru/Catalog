<?php

/**
* помощник view для построчно формы фильтра товара
* нужен только для добавления в базовый помощгник модифицированного помощника 
* FormElement, т.к. нет средств для его изменения
*/

namespace Mf\Catalog\View\Helper;

use Laminas\Form\View\Helper\FormRow;
use Laminas\Form\View\Helper\FormElement;

class FilterFormRow extends FormRow 
{


    public function setElementHelper(FormElement $elementHelper)
    {
        $this->elementHelper=$elementHelper;
    }
}