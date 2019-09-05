<?php

namespace Mf\Catalog\Entity;

class Brend
{

    protected $id = null;

    protected $name = null;

    protected $url = null;

    protected $info = null;

    protected $title = null;

    protected $keywords = null;

    protected $description = null;

    protected $logo = null;

    protected $anons = null;

    protected $poz = null;

    protected $id1c = null;

    public function setId($id)
    {
        $this->id=$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name=$name;
    }

    public function getName()
    {
        return $this->name;
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

    public function setLogo($logo)
    {
        $this->logo=$logo;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setAnons($anons)
    {
        $this->anons=$anons;
    }

    public function getAnons()
    {
        return $this->anons;
    }

    public function setPoz($poz)
    {
        $this->poz=$poz;
    }

    public function getPoz()
    {
        return $this->poz;
    }

    public function setId1c($id1c)
    {
        $this->id1c=$id1c;
    }

    public function getId1c()
    {
        return $this->id1c;
    }


}
