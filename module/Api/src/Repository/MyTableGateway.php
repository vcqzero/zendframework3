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
    const ITEM_COUNT_PER_PAGE = 10;
    
    /**
    * 通过id更新数据
    * 
    * @param array $set 
    * @param int $id 
    * @return bool       
    */
    public function updateById($set, $id)
    {
        $where = ['id'=>$id];
        $affect_rows = $this->update($set, $where);
        return $affect_rows > 0;
    }
    /**
    * 从本数据表中查询一条数据
    * 
    * @param int|array|Where $id_or_where 
    * @return array        
    */
    public function selectOne($id_or_where)
    {
        $select = $this->getSql()->select();
        $select ->limit(1);
        if (is_array($id_or_where) || $id_or_where instanceof Where) {
            $where = $id_or_where;
        }else {
            $id = $id_or_where;
            $where = ['id' => $id ];
        }
        //set wehere
        $select->where($where);
        
        //do select
        $res = $this->selectWith($select);
        //get the first as array
        $res = $res->current()->getArrayCopy();
        return $res;
    }
    
    /**
    * 查询数量
    * 
    * @param array $where
    * @return int $count       
    */
    public function count($where=[])
    {
        $count = $this->select($where)->count();
        return $count;
    }
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
