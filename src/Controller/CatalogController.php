<?php
/**
 */

namespace Mf\Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ADO\Service\RecordSet;
use ADO\Service\Command;



class CatalogController extends AbstractActionController
{
    protected $cache;
    protected $catalog;
    
    
    public function __construct($catalog,$cache)
    {
        $this->catalog=$catalog;
        $this->cache=$cache;
    }
    public function indexAction()
    {

    return new ViewModel();
    }
    
    
    
    public function listAction()
    {
        $url=$this->params('catalog',"");
        $page=(int)$this->params('page',0);
      //  try {
            //проверим нижнюю границу номера страницы
            if ($page==1){
                throw new Exception("Указан номер страницы 1 в URL, это приводит к дубляжу.");
            }
            if (empty($page)){/*номер страницы не указан, значит это первая*/
                $page=1;
            }

            $node=$this->catalog->getNodeInfo($url);
            $nodes=$this->catalog->GetNodeList($node->getId());
            $pagination=$this->catalog->GetTovarList($url);
            /*настроим пагинатор*/
            $pagination->setItemCountPerPage (51);
            $pagination->setPageRange (18);
            $pagination->setCurrentPageNumber($page);
        
        //проверим верхнюю границу кол-ва страниц
        if ($page > $pagination->count() && $pagination->count()>0){
            throw new \Exception("Номер текущей страницы больше общего количества страниц");
        }
        
        $breadcrumb=$this->catalog->GetPath($node->getId());
        return new ViewModel([
            "node"=>$node,
            "nodes"=>$nodes,
            "pagination"=>$pagination,
            "page"=>$page,
            "breadcrumb"=>$breadcrumb
        ]);

     /*   } catch (\Exception $e) {
            //любое исключение - 404
            $this->getResponse()->setStatusCode(404);
        }*/
    }
    
public function tovarAction()
{
   // try {
            $url=$this->params('url', 0);
            $category=$this->params('catalog',"");
            $node=$this->catalog->getNodeInfo($category);
            $info=$this->catalog->GetTovarInfo($category,$url);
            $breadcrumb=$this->catalog->GetPath($node->getId());
    $parameters=$this->catalog->getParameters($url);

            return new ViewModel([
                "info"=>$info,
                "node"=>$node,
                "breadcrumb"=>$breadcrumb,
                "parameters"=>$parameters
            ]);
      /*  } catch (\Exception $e) {
            //любое исключение - 404
            $this->getResponse()->setStatusCode(404);
        }*/

}

}
