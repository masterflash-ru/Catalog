<?php
/**
 */
namespace Mf\Catalog;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;

class Module
{

public function getConfig()
{
    return include __DIR__ . '/../config/module.config.php';
}

public function onBootstrap(MvcEvent $event)
{
	$eventManager = $event->getApplication()->getEventManager();
    $ServiceManager=$event->getApplication()-> getServiceManager();
    $sharedEventManager = $eventManager->getSharedManager();
    // объявление слушателя для изменения макета на админский + проверка авторизации root
    $sharedEventManager->attach("Mf", MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 1);
    
    //смотрим конфигурацию на предмет обработки загрузки/импорта
    $config=$ServiceManager->get("config");
    if ($config["catalog"]["import"]["1c"]["service_import"]){
        //стандартный обработчик загрузки из 1С в каталог после импорта
        $sharedEventManager->attach("simba.1c", "catalogImportComplete", function(Event $event) use ($ServiceManager,$config){
            $service=$ServiceManager->build($config["catalog"]["import"]["1c"]["service_import"]);
            return $service->CatalogImport();
        },2);

        //стандартный обработчик загрузки из 1С в каталог после импорта
        $sharedEventManager->attach("simba.1c", "catalogOffersComplete", function(Event $event) use ($ServiceManager,$config){
            $service=$ServiceManager->build($config["catalog"]["import"]["1c"]["service_import"]);
            return $service->CatalogOffers();
        },2);
        //стандартный обработчик загрузки из 1С в каталог очистка каталога
        $sharedEventManager->attach("simba.1c", "catalogTruncate", function(Event $event) use ($ServiceManager,$config){
            $service=$ServiceManager->build($config["catalog"]["import"]["1c"]["service_import"]);
            return $service->CatalogTruncate();
        },2);
    }
    //обработчик дополнительных реквизитов, они индивидуальные, поэтому его нет в стандартной реализации
    if ($config["catalog"]["import"]["1c"]["service_requisites"]){
        $sharedEventManager->attach("simba.1c", "catalogImportComplete", function(Event $event) use ($ServiceManager,$config){
            $service=$ServiceManager->build($config["catalog"]["import"]["1c"]["service_requisites"]);
            return $service->CatalogRequisites();
        },2);
    }

}

/*слушатель для проверки авторизован ли админ*/
public function onDispatch(MvcEvent $event)
 {
    //для данного модуля изменить макет
    $controllerName = $event->getRouteMatch()->getParam('controller', null);
    if ($controllerName!="Mf\Catalog\Controller\AdminController") { return; }
   
    $controller = $event->getTarget();
    
    $user=$controller->User()->getUserId();
    /*имя метода контроллера*/
    $actionName = $event->getRouteMatch()->getParam('action', null);
    $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
    

    $viewModel = $event->getViewModel();
    /*проверяем доступ по имени контроллера и метода, без Action*/
    $acl=$controller->acl()->isAllowed("x",[$controllerName,$actionName]);
    $viewModel->setTemplate('layout/admin_layout');
    if (!$acl){
        if ($user!=1){
            //авторизованы, но доступ запрещен
            $controller->redirect()->toRoute('accessdenied');
            return;
        } else {
            //получилось, что root -у доступа нет, выводим сообщение
            echo "получилось, что root -у доступа нет к <b>{$controllerName}/{$actionName}</b><br>Проверьте таблицу доступа";
            return;
        }
        $viewModel->setTemplate('layout/admin_layout');
        return;
    }
	
 
}

}
