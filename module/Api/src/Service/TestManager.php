<?php
/**
* 关于用户表的增删改查
* 
*/
namespace Api\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\Event;

class TestManager implements EventManagerAwareInterface
{
    protected $events;
    
    public function __construct()
    {
    }
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers([
            __CLASS__,
            get_class($this)
        ]);
        $this->events = $events;
    }

    public function getEventManager()
    {
        if (! $this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
    
    public function event(Event $e)
    {
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump($e->getParams());
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump('EVENT SUCCESS');
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
    }

}

