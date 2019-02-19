<?php
namespace Mf\Catalog\Service;

use ADO\Service\RecordSet;
use ADO\Service\Command;
use Mf\Stream\Entity\Item;
use Mf\Stream\Entity\SeoOptions;
use Exception;


class Admin 
{
	
    protected $connection; 
    protected $cache;
	protected $config;


    public function __construct($connection, $cache,$config) 
    {
        $this->connection = $connection;
        $this->cache = $cache;
		$this->config=$config;
    }




}

