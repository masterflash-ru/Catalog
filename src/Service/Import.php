<?php
namespace Mf\Catalog\Service;

use Exception;

class Import 
{
    protected $connection;
    
    
    public function __construct($connection) 
    {
        $this->connection=$connection;
    }
    
    /**
    * вызывается после ипорта раздела Import из 1С
    */
	public function CatalogImport()
    {
    }

    /**
    * вызывается после ипорта раздела Offers из 1С
    */
	public function CatalogOffers()
    {

    }
}
