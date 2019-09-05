<?php

namespace Mf\Catalog\Entity;

class Tovar
{

 protected $id = null;

    protected $public = null;

    protected $xml_id = null;

    protected $sku = null;

    protected $name = null;

    protected $quantity = null;

    protected $url = null;

    protected $poz = null;

    protected $anons = null;

    protected $info = null;

    protected $title = null;

    protected $keywords = null;

    protected $description = null;

    protected $new_session = null;

    protected $new_date = null;

    protected $psku = null;

    public function setId($id)
    {
        $this->id=$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPublic($public)
    {
        $this->public=$public;
    }

    public function getPublic()
    {
        return $this->public;
    }

    public function setXml_id($xml_id)
    {
        $this->xml_id=$xml_id;
    }

    public function getXml_id()
    {
        return $this->xml_id;
    }

    public function setSku($sku)
    {
        $this->sku=$sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setName($name)
    {
        $this->name=$name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setQuantity($quantity)
    {
        $this->quantity=$quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setUrl($url)
    {
        $this->url=$url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setPoz($poz)
    {
        $this->poz=$poz;
    }

    public function getPoz()
    {
        return $this->poz;
    }

    public function setAnons($anons)
    {
        $this->anons=$anons;
    }

    public function getAnons()
    {
        return $this->anons;
    }

    public function setInfo($info)
    {
        $this->info=$info;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setTitle($title)
    {
        $this->title=$title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setKeywords($keywords)
    {
        $this->keywords=$keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setDescription($description)
    {
        $this->description=$description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setNew_session($new_session)
    {
        $this->new_session=$new_session;
    }

    public function getNew_session()
    {
        return $this->new_session;
    }

    public function setNew_date($new_date)
    {
        $this->new_date=$new_date;
    }

    public function getNew_date()
    {
        return $this->new_date;
    }

    public function setPsku($psku)
    {
        $this->psku=$psku;
    }

    public function getPsku()
    {
        return $this->psku;
    }


}
