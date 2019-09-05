<?php

namespace Mf\Catalog\Entity;

class Catalog
{

    protected $id = null;

    protected $tovar_category = null;

    protected $brend = null;

    protected $name = null;

    protected $articul = null;

    protected $model = null;

    protected $url = null;

    protected $info = null;

    protected $price = null;

    protected $title = null;

    protected $keywords = null;

    protected $description = null;

    protected $ostatok = null;

    protected $id1c = null;

    protected $properties = null;

    public function setId($id)
    {
        $this->id=$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTovar_category($tovar_category)
    {
        $this->tovar_category=$tovar_category;
    }

    public function getTovar_category()
    {
        return $this->tovar_category;
    }

    public function setBrend($brend)
    {
        $this->brend=$brend;
    }

    public function getBrend()
    {
        return $this->brend;
    }

    public function setName($name)
    {
        $this->name=$name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setArticul($articul)
    {
        $this->articul=$articul;
    }

    public function getArticul()
    {
        return $this->articul;
    }

    public function setModel($model)
    {
        $this->model=$model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setUrl($url)
    {
        $this->url=$url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setInfo($info)
    {
        $this->info=$info;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setPrice($price)
    {
        $this->price=$price;
    }

    public function getPrice()
    {
        return $this->price;
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

    public function setOstatok($ostatok)
    {
        $this->ostatok=$ostatok;
    }

    public function getOstatok()
    {
        return $this->ostatok;
    }

    public function setId1c($id1c)
    {
        $this->id1c=$id1c;
    }

    public function getId1c()
    {
        return $this->id1c;
    }

    public function setProperties($properties)
    {
        $this->properties=$properties;
    }

    public function getProperties()
    {
        return $this->properties;
    }


}
