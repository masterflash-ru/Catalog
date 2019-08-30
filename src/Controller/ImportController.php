<?php
/**

*/

namespace Mf\Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Exception;

class ImportController extends AbstractActionController
{

    protected $import;


    /**
    * $import - сервис импорта из этого пакета
    */
    public function __construct($import)
    {
        $this->import=$import;
    }


    /**
    * обработка загруженного из 1С  по расписанию
    * обработка файлов прикрепленных к товару
    */
    public function cronAction()
    {
        $rez=$this->import->cron();
        return new JsonModel($rez);
    }


}
