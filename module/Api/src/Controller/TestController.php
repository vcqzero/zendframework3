<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\JsonModel;
use Zend\View\Model\JsonModel;
use Zend\Log\Logger;
use Zend\Cache\Storage\Adapter\Memcache;
use Zend\Cache\Storage\Adapter\Memcached;
use Api\Service\TestManager;

class TestController extends AbstractActionController
{
    private $Logger;
    private $Memcached;
    private $TestManager;
    public function __construct(
        Logger $Logger,
        Memcache $Memcached,
        TestManager $TestManager
        )
    {
        $this->Logger= $Logger;
        $this->Memcached = $Memcached;
        $this->TestManager = $TestManager;
    }
    
    //index
    public function indexAction()
    {
        
        $key = 'name';
        $this->Memcached->setItem($key, 'qinchongq');
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump($this->Memcached->getItem($key));
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        $view = new JsonModel();
        return $view;
    }
    
    public function eventAction()
    {
        //此方法在外部定义attach和trigger方法
        //可以在监听者内部顶级监听方法
        //监听者需实现EventManagerAwareInterface方法
        $TestManeger = $this->TestManager;
        $name = 'TEST_EVET';
        $TestManeger->getEventManager()->attach($name, [$TestManeger, 'event']);
        $TestManeger->getEventManager()->trigger($name, $TestManeger, ['qinchong']);
        
    }
}
