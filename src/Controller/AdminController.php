<?php
/**

*/

namespace Mf\Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use ADO\Service\RecordSet;
use Zend\Session\Container;
use Exception;

class AdminController extends AbstractActionController
{

    protected $connection;


    public function __construct($connection)
    {
        $this->connection=$connection;
    }


    /**
    * создание нового товара
    * создается специальная пустая запись в каталоге товаров
    */
    public function tovarnewAction()
    {
        $rez=[];
        $session=new Container();
        $session_id=$session->getManager()->getId();
        
        $type=$this->params()->fromQuery("type",0);
        
        //удалим брошенные новые записи
        $a=0;
        $this->connection->Execute("delete from catalog_tovar where new_date and new_date > date_add(now(), interval 1 hour)",$a,adExecuteNoRecords);
        
        $rs=new RecordSet();
        $rs->CursorType =adOpenKeyset;
        $rs->Open("select * from catalog_tovar where new_date and new_session='{$session_id}'",$this->connection);
        if ($rs->EOF){
            $rs->AddNew();
        }
        $rs->Fields->Item["new_date"]->Value=date("Y-m-d H:i:s");
        $rs->Fields->Item["new_session"]->Value=$session_id;
        $rs->Update();
        
        //получим ID новой записи
        $rez["id"]=$rs->Fields->Item["id"]->Value;
        $rez["new_session"]=$rs->Fields->Item["new_session"]->Value;
        
        
        return new JsonModel($rez);
    }

}
