<?php
/*
* помощник view для вывода навигационных элементов
* при необходимости его можно расширить новым объектом для генерации меню из каталога, например.
*/

namespace Mf\Catalog\View\Helper;

//use Mf\Navigation\View\Helper\Menu as MfMenu;
use Zend\View\Helper\AbstractHelper;
use ADO\Service\RecordSet;
use Zend\Navigation\Service\ConstructedNavigationFactory;
use Zend\Navigation\Navigation as ZFNavigation;
use Mf\Navigation;
use Exception;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * помощник - вывода меню
 */
class MenuCategory extends AbstractHelper 
{
    protected $connection;
    protected $cache;
    protected $rs;
    protected $container;
    protected $_default=[
        "zf3"=>[ /*параметры для меню стандарта ZF3*/
            "locale"=>"ru_RU",               //имя локали
            "ulClass"=>"navigation",         //класс для ul элемента (сдля стандартного ZEND меню)
            "indent"=>"",
            "minDepth"=>1,                   //минимальный уровень вывода
            "maxDepth"=>null,                //максимальный уровень
            "liActiveClass"=>"active",       //имя класса для активного пункта
            "escapeLabels"=>true,            //экранировать метки да/нет
            "addClassToListItem"=>false,
            "OnlyActiveBranch"=>false,
            "tpl"=>null,
        ],
    ];

/*конструктор
*параметры передаются из фабрики
*/
public function __construct ($connection,$cache,$container)
  {
    $this->connection=$connection;
    $this->cache=$cache;
    $this->container=$container;
  }

/*
*$options - массив опций (см. выше дефолтные объявления), 
* пустой массив, по умолчанию используется стандартный помощник ZF3
* 
*/
public function __invoke($sysname=null, $options=[])
{
    $result = false;
    $key="catalog_category";

    $menu = $this->cache->getItem($key, $result);
    if (!$result || true){

        /*получить массив для передачи его в Navigation*/
        $pages=$this->getMenu();
        /*генерируем объект Navigation от ZF3*/
        $navigation = $this->createNavigation($pages);
        $menu= $this->render($navigation,$options);
      $this->cache->setItem($key, $menu);
      $this->cache->setTags($key,["catalog_category","catalog"]);

    }
    return $menu;
}
/*
* генерирует объект Navigation 
* $pages - массив (пока) страниц, пригодный для генерации Navigation
* возвращает Navigation с контейнером
*/
public function createNavigation(array $pages)
{
    $factory    = new ConstructedNavigationFactory($pages);
    return $factory->createService($this->container);
}

/*
* получить из базы меню данного имени и сгенерировать дерево в виде массива
* $sysname - системное имя меню,
* $locale - локаль, например, ru_RU
* возвращает массив пригодный для передачи его в Navigation
* результирующий массив кешируется
*/
public function getMenu($sysname=null, $locale=null)
{
   $rs=new RecordSet();
    $rs->MaxRecords=0; 
    $rs->CursorType = adOpenKeyset;
    $rs->open("select c.* 
    from (
        select id,subid,url,name,
        (EXISTS(select * from catalog_category2tovar where `catalog_category`=catalog_category.id)) as tovars
            from catalog_category where public>0
                order by poz
    ) as c 
        where id in(select subid from catalog_category) or tovars>0",$this->connection);

        $array=[];
        while (!$rs->EOF){
            $r=[];
            $r["id"]=$rs->Fields->Item["id"]->Value;
            $r["subid"]=$rs->Fields->Item["subid"]->Value;
            $r["label"]=$rs->Fields->Item['name']->Value;
            if ((int)$rs->Fields->Item['tovars']->Value>0  ){
                $r["uri"]="/catalog/".$rs->Fields->Item['url']->Value;
                $r["class"]="has_tovar";
            } else {
                $r["uri"]='#';
            }
    
            $array[]=$r;
            $rs->MoveNext();
        }
        
        $tree=[];
        
        foreach($array as $cat) {
            $tree[$cat['id']] = $cat;
            unset($tree[$cat['id']]['id']);
        }
        
        $tree['0'] = array(
            'subid' => '',
            'label' => 'Корень',
        );
        
        foreach ($tree as $id => $node) {
            if (isset($node['subid']) && isset($tree[$node['subid']])) {
                $tree[$node['subid']]['pages'][$id] =& $tree[$id];
            }
        }
        if (!empty($tree[0]['pages'])){
            $menu= $tree[0]['pages'];
        } else {
            $menu=[];
        }
        


    return $menu;
}
    
    public function render(ZFNavigation $navigation,array $options)
    {
        $options=$this->normalizeOptions($options);
        $view=$this->getView();
        if ($options["menu_type"]=="zf3"){
            /*стандартный из ZF3 прокси navigation*/
            $nav_proxy="Navigation";
        } else {
            /*подменный прокси, в котором прописаны разные виды генерации*/
            $nav_proxy=$options["menu_type"]."Navigation";
        }

        $nav=$view->$nav_proxy();

        $nav =$nav->menu($navigation)
            ->setulClass($options["ulClass"])
            ->setminDepth($options["minDepth"])
            ->setmaxDepth($options["maxDepth"])
            ->setindent($options["indent"])
            ->setliActiveClass($options["liActiveClass"])
            ->escapeLabels($options["escapeLabels"])
            ->setaddClassToListItem($options["addClassToListItem"])
            ->setOnlyActiveBranch($options["OnlyActiveBranch"]);

      if(!empty($options["tpl"]) ){
        //если указан шаблон, то применим его
        return $nav->setPartial($options["tpl"])
                ->renderPartialWithParams($options);
      } 
        //стандартный рендер меню
        return $nav->setPartial(null)->render();
    }
/*
* нормализация опций, возвращаются опции дополненные значениями по умолчанию 
* возвращает нормализованный массив опций
*/
public function normalizeOptions(array $options)
{
    $menu_type=array_keys($options);
    
    if (empty($menu_type)){
        $menu_type="zf3";
        $options=$this->_default[$menu_type];
    } else {
        $menu_type=strtolower($menu_type[0]);
        if (!isset($this->_default[$menu_type])) {
            throw new  Exception("Не допустимый тип навигации $menu_type");
        }
        if (!isset($options[$menu_type]) || !is_array($options[$menu_type])) {
            throw new  Exception("Не допустимые параметры для генерации навигации");
        }

        $options=array_replace_recursive($this->_default[$menu_type],$options[$menu_type]);
    }
    
    $options["menu_type"]=$menu_type;
    return $options;
}

}