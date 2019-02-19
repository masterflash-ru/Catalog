<?php
/**
 */
namespace Mf\Catalog;

use Zend\Mvc\MvcEvent;


class Module
{

public function getConfig()
{
    return include __DIR__ . '/../config/module.config.php';
}

public function onBootstrap(MvcEvent $event)
{
	$eventManager = $event->getApplication()->getEventManager();
    $sharedEventManager = $eventManager->getSharedManager();
    // объявление слушателя для изменения макета на админский + проверка авторизации root
    $sharedEventManager->attach("Mf", MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 1);
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
