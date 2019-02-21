<?php
/**

*/

namespace Mf\Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Lib\Ioline;
use Exception;

class AdminController extends AbstractActionController
{
      protected $adminService;
    protected $container;


public function __construct ($adminService,$container)
{
    $this->adminService=$adminService;
    $this->container=$container;//\Zend\Debug\Debug::dump(unserialize(base64_decode($_GET['get_interface_input'])));
    //unset($_GET["get_interface_input"]);
}


/**
* создание нового обычного товара - вывод макета
*/
public function saveAction()
{
    $view= new ViewModel(
			[
                "container"=>$this->container,
                "new"=>true,
                "torg"=>false
			]);
	return $view;

}

/**
* создание нового обычного товара - вывод макета
*/
public function createtovarAction()
{
    $view= new ViewModel(
			[
                "container"=>$this->container,
                "new"=>true,
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
                "new"=>true,
                "torg"=>true
			]);
    $view->setTemplate("mf/catalog/admin/edittovar");
	return $view;

}
    
/**
* редактор товара - вываод макета
*/
public function edittovarAction()
{
    $view= new ViewModel(
			[
                "container"=>$this->container,
                "torg"=>false
			]);
    $view->setTemplate("mf/catalog/admin/edittovar");
	return $view;

}
    
}
