<?php

namespace Mf\Catalog\Entity;

class Properties
{

    protected $catalog_tovar = null;

    protected $catalog_properties_list = null;

    protected $catalog_properties = null;

    protected $value = null;

    protected $properties_name = null;

    public function setCatalog_tovar($catalog_tovar)
    {
        $this->catalog_tovar=$catalog_tovar;
    }

    public function getCatalog_tovar()
    {
        return $this->catalog_tovar;
    }

    public function setCatalog_properties_list($catalog_properties_list)
    {
        $this->catalog_properties_list=$catalog_properties_list;
    }

    public function getCatalog_properties_list()
    {
        return $this->catalog_properties_list;
    }

    public function setCatalog_properties($catalog_properties)
    {
        $this->catalog_properties=$catalog_properties;
    }

    public function getCatalog_properties()
    {
        return $this->catalog_properties;
    }

    public function setValue($value)
    {
        $this->value=$value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setProperties_name($properties_name)
    {
        $this->properties_name=$properties_name;
    }

    public function getProperties_name()
    {
        return $this->properties_name;
    }


}
