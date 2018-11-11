<?php
namespace Api\Repository;

use Zend\Db\TableGateway\TableGateway; 
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Where;
use Zend\Db\ResultSet\ResultSet;
use Zend\Console\Prompt\Select;

class MyTableGateway extends TableGateway
{
    const ITEM_COUNT_PER_PAGE = 9;
    
    /**
    * 获取当前table的分页数据
    * 
    * @param int $page  
    * @param Where||array $where  
    * @return Paginator 可以当做二维数组   
    */
    public function paginator($page = 1, $where = [])
    {
        $DbAdapter = $this->getAdapter();
        $table     = $this->getTable();
        $select    = $this->getSql()->select();
        $select    -> where($where);
        $ResultSet  = new ResultSet(ResultSet::TYPE_ARRAY);
        $adapter    = new DbSelect($select, $DbAdapter, $ResultSet);
        $paginator  = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        Paginator::setDefaultItemCountPerPage(self::ITEM_COUNT_PER_PAGE);
        return $paginator;
    }
    
    /**
    * 根据 select 获取分页
    * 不区分table
    * 
    * @param int $page  
    * @param Select $Select 
    * @return Paginator 可以当做二维数组   
    */
    public function paginatorWith($page, Select $Select)
    {
        $DbAdapter = $this->getAdapter();
        $table     = $this->getTable();
        $ResultSet  = new ResultSet(ResultSet::TYPE_ARRAY);
        $adapter    = new DbSelect($Select, $DbAdapter, $ResultSet);
        $paginator  = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        Paginator::setDefaultItemCountPerPage(self::ITEM_COUNT_PER_PAGE);
        return $paginator;
    }
}
