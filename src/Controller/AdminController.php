<?php
/**

*/

namespace Mf\Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Exception;

class AdminController extends AbstractActionController
{
      protected $adminService;
    protected $container;


public function __construct ($adminService,$container)
{
    $this->adminService=$adminService;
    $this->container=$container;
    //unset($_GET["get_interface_input"]);
}



/**
* создание нового обычного товара - вывод макета
*/
public function createtovarAction()
{
    $view= new ViewModel(
			[
                "container"=>$this->container,
                "torg"=>false
			]);
    $view->setTemplate("mf/catalog/admin/edittovar");
	return $view;

}

/**
* создание товара с торговыми предлоежниями - вываод макета
*/
public function createtovar1Action()
{
    $view= new ViewModel(
			[
                "container"=>$this->container,
                "torg"=>true
			]);
    $view->setTemplate("mf/catalog/admin/edittovar");
	return $view;

}
    
    
}
