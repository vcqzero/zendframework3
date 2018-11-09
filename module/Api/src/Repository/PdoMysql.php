<?php
namespace Api\Repository;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Log\Logger;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Zend\Db\Sql\Insert;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\PreparableSqlInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Where;
/**
* 重新写mysqlPdo
* 将常用的增删改查做好封装，不必每个表都写一遍。
* 做好日志记录和异常处理
*/
final class PdoMysql
{
    private $DbAdapter;
    //Sql实例
    private $Sql;
    private $Select;
    
    //Logger
    private $Logger;
    
    //isDebug
    private $isDebug;
    
    //sql语句
    private $sqlString;
    
    //数据库操作错误信息
    private $errorMessage;
    
    //delete或update操作之后，受影响的行数
    private $affectedRows;
    
    //插入数据itemID
    private $lastInsertId;
    
    //查询到的数据数量
    private $count;
    
    //数据表名称
    private $tableName;
    /**
     * @return the $tableName
     */
    public function getTableName()
    {
        $tableName = $this->tableName;
        
        if(empty($tableName))
        {
            throw new \Exception('请先设置tableName');
        }
        
        return $tableName;
    }

    /**
     * @param field_type $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param void 
     */
    public function startDebug()
    {
        $this->isDebug = true;
    }
    public function stopDebug()
    {
        $this->isDebug = false;
    }

    /**
     * 获取查询到的数据数量
     * @return the $count
     */
    public function getCount()
    {
        return $this->count;
    }
    /**
     * insert操作之后，新插入的itemID
     *
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }
    /**
     * delete操作之后，受影响的行数
     *
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->affectedRows;
    }
    
    /**
     * @param string $sqlString
     */
    private function setSqlString($sqlString)
    {
        $this->sqlString = $sqlString;
        //然后判断是否需要记录到debug文件中作为记录
        if ($this->isDebug)
        {
            $message = 'DEBUG模式下输出sql语句' .PHP_EOL;
            $message .= $sqlString . PHP_EOL;
            $this->Logger->log(Logger::DEBUG, $message);
        }
    }
    
    public function __construct(Adapter $DbAdapter, Logger $Logger)
    {
        $this->DbAdapter    = $DbAdapter;
        $this->Logger       = $Logger;
        $this->Sql          = new Sql($DbAdapter);
    }
    
    
    private function createWhere($id_or_where)
    {
        if(is_array($id_or_where) || $id_or_where instanceof Where )
        {
            $where = $id_or_where;
        }else {
            $where = [
                'id' => (int)$id_or_where
            ];
        }
        return $where;
    }
    /**
     * 新增一条数据
     *
     * @param string $tableName 数据表名称
     * @param array $values 数据内容
     * @return bool
     */
    public function insert($values)
    {
        $tableName  = $this->getTableName();
        $Insert     = new Insert($tableName);
        $Insert     ->values($values);
        
        //执行插入操作
        $Result      = $this->doExecute($Insert);
        if ($Result instanceof ResultInterface)
        {
            $this->lastInsertId= $Result->getGeneratedValue();
            return true;
        }else
        {
            $this->lastInsertId = null;
            return false;
        }
    }
    
    /**
     * 根据id 或者 where条件update数据表中的数据
     * 所有的update不能更改id,会自动删除更改数据中的id字段
     * 
     * @param int || Where  the id or where
     * @param array $set
     * @return bool
     */
    public function update($id_or_where, $set)
    {
        //get where
        $where = $this->createWhere($id_or_where);
        $tableName  = $this->getTableName();
        $Update     = new Update($tableName);
        $Update     ->where($where);
        //unset id
        unset($set['id']);
        $Update     ->set($set);
        //do
        $result = $this->doExecute($Update);
        if ($result instanceof ResultInterface)
        {
            $this->affectedRows = $result->getAffectedRows();
            return true;
        }else
        {
            $this->affectedRows = null;
            return false;
        }
    }
    
    /**
     * 根据where条件删除数据表中的数据
     *
     * @param string $tableName 数据表名称
     * @param array $where 查询条件，可以是数组或者是Where对象
     * @return bool
     */
    public function delete($id_or_where)
    {
        //get where
        $where = $this->createWhere($id_or_where);
        $tableName  = $this->getTableName();
        //执行更新操作
        $Delete = new Delete($tableName);
        $Delete ->where($where);
        $result = $this->doExecute($Delete);
        if ($result instanceof ResultInterface)
        {
            $this->affectedRows = $result->getAffectedRows();
            return true;
        }else
        {
            $this->affectedRows = null;
            return false;
        }
    }
    
    /**
     * 获取分页数据，已实体形式
     * 具体分页设置待返回数据后进行设置
     *
     * @param int           $page 
     * @param Where||Select $where_or_Select
     * @param string||array $order  
     *  
     * @return Paginator $paginator
     */
    public function paginator($page, $where_or_Select, $order=null)
    {
        
        $where_or_Select = empty($where_or_Select) ? [] : $where_or_Select;
        $page = (int)$page;
        $page = empty($page) ? 1 : $page;
        
        if ($where_or_Select instanceof Select)
        {
            $Select = $where_or_Select;
        }else {
            $tableName  = $this->getTableName();
            $Select     = new Select($tableName);
            $where      = $where_or_Select;
            
            //delete the empty key
            foreach ($where as $key=>$val)
            {
                if (empty($val))
                {
                    unset($where[$key]);
                }
            }
            //delete the page
            unset($where['page']);
            $Select     ->where($where);
            if ($order)
            {
                $Select     ->order($order);
            }
        }
        //get paginator
        $paginator  = $this->initPaginator($Select);
        $paginator  ->setCurrentPageNumber($page);
        $paginator  ->setDefaultItemCountPerPage(8);
        
        return $paginator;
    }
    
    
    /**
     * query data by id or where
     *
     * @param int||Where $id or Where 数据表名称 默认 null
     * @param string||array $order
     * @return array or false
     */
    public function findOne($id_or_where, $order=null)
    {
        $where  = $this->createWhere($id_or_where);
        $Select = new Select();
        $Select ->limit(1);
        $Select ->where($where);
        $tableName = $this->getTableName();
        $Select->from($tableName);
        if ($order)
        {
            $Select->order($order);
        }
        $resultSet = $this->doQuery($Select);
        
        if (empty($this->count))
        {
            return false;
        }else
        {
            return current($resultSet);
        }
    }
    
    /**
     * find  by select
     *
     * @param Select
     * @return array || false 二维数组或false on falied
     */
    public function findBy(Select $Select) 
    {
        return $this->doQuery($Select);
    }
    
    /**
     * find all by the where
     *
     * @param array || Where $where
     * @param string || array $order
     * @param int $limit
     * @return array || false 二维数组或false on falied
     */
    public function findAll($where = [], $order=null, $limit = null)
    {
        $Select = new Select();
        $Select ->where($where);
        $tableName = $this->getTableName();
        $Select->from($tableName);
        if ($order) 
        {
            $Select->order($order);
        }
        if ($limit) 
        {
            $Select->limit($limit);
        }
        return $this->doQuery($Select);
    }
    
    /**
     * query data by where
     *
     * @param  Select $Select
     * @return array || false 二维数组或false on falied
     */
    private function doQuery(Select $Select)
    {
        //执行获取执行结果
        $Result = $this->doExecute($Select);
        if ($Result instanceof ResultInterface)
        {
            $this->count = $Result->count();
            $ResultSet = new ResultSet(ResultSet::TYPE_ARRAY);
            $ResultSet->initialize($Result);
            return $ResultSet->toArray();
        }else
        {
            $this->count = 0;
            return false;
        }
    }
    
    /**
     * 根据where语句查询符合条件的数量
     *
     * @param  array||Where $where
     * @return int
     */
    public function count($where)
    {
        $Select = new Select();
        $Select->where($where);
        $tableName = $this->getTableName();
        $Select->from($tableName);
        $this->doQuery($Select);
        return $this->count;
    }
    /**
    * 获取数据库连接对象
    * 可用于开启事务操作
    * 
    * @param  
    * @return ConnectionInterface      
    */
    public function getConnection()
    {
        $connection=$this->DbAdapter->getDriver()->getConnection();
        
        return $connection;
    }
    
    /**
     * 执行sql语句，得到result
     *
     * @param  PreparableSqlInterface $SqlObject
     * @return ResultInterface || false 数据库错误时返回false
     */
    private function doExecute(PreparableSqlInterface $SqlObject)
    {
        try{
            $Sql = $this->Sql;
            //记录sql语句
            $this->setSqlString($Sql->buildSqlString($SqlObject));
            //执行sql语句
            $Sth    = $Sql->prepareStatementForSqlObject($SqlObject);
            $Result = $Sth->execute();
            $this->setErrorMessage('');
        }catch (\Exception $e ){
            $errorMessage = $e->getMessage();
            $traceString  = $e->getTraceAsString();
            $this->setErrorMessage($errorMessage, $traceString);
            $Result = false;
        }
        return $Result;
    }
    
    /**
     * 获取基本的分页对象
     * 当Entity = null时返回关联数组集合
     *
     * @param  Sql $Sql
     * @param  Select $Select
     * @param  $Entity 默认为null
     * @return Paginator
     */
    private function initPaginator(Select $Select)
    {
        $Sql = $this->Sql;
        $this->setSqlString($Sql->buildSqlString($Select));
        //get paginator
        $DbAdapter  = $this->DbAdapter;
        $Entity     = $this->getEntity();
        $resultSet  = new ResultSet(ResultSet::TYPE_ARRAY);
        $dbSelect   = new DbSelect($Select, $DbAdapter, $resultSet);
        $paginator  = new Paginator($dbSelect);
        
        return $paginator;
    }
    
    /**
     * 错误信息处理
     * 1 设置错误信息
     * 2 记录到logger中
     *
     * @param
     * @return
     */
    private function setErrorMessage($errorMessage, $traceString = null)
    {
        $this->errorMessage = $errorMessage;
        if (empty($errorMessage))
        {
            return ;
        }
        //记录错误信息到debug文件中
        $message    = '数据库操作错误: ' . PHP_EOL;
        $message    .= '错误信息->' . $errorMessage . PHP_EOL;
        $message    .= 'sql语句->' . $this->getSqlString() . PHP_EOL;
        $message    .= 'trace->' . PHP_EOL;
        $message    .= $traceString;
        $this->Logger->log(Logger::DEBUG, $message);
    }
}
