<?php 
namespace WebTool\DB;

/**
 * 有关MySQLI操作
 * X-Wolf
 * 2019-1-31
 */
class MysqliDB
{
	private static $instance = null;

	private $host = '127.0.0.1';

	private $port = '3306';

	private $username = 'root';

	private $password = 'root';

	private $db = 'test';

	private $charset = 'utf8';

	private $link;

	private function __construct($conf=[])
	{
		if($conf){
			if( isset($conf['host']) && $conf['host']) $this->host = $conf['host'];
			if( isset($conf['port']) && $conf['port']) $this->port = $conf['port'];
			if( isset($conf['username']) && $conf['username']) $this->username = $conf['username'];
			if( isset($conf['password']) && $conf['password']) $this->password = $conf['password'];
			if( isset($conf['db']) && $conf['db']) $this->db = $conf['db'];
			if( isset($conf['charset']) && $conf['charset']) $this->charset = $conf['charset'];
		}
		
		$this->connect();
		
		$this->setCharset($this->charset);
	}

	// 连接数据库
	private function connect()
	{
		$this->link = new Mysqli($this->host,$this->username,$this->password,$this->db,$this->port);
		if($this->link->connect_errno){
			trigger_error("Connect database failure Errno:".$this->link->connect_errno.' Error:'.$this->link->connect_error,E_USER_ERROR);
			
		}

	}

	// 外部调用
	public static function getInstance($conf=[])
	{
		if( is_null(self::$instance) ){
			self::$instance = new self($conf);
		}
		return self::$instance;
	}

	// 设置字符集
	public function setCharset($charset)
	{
		$this->query('set names '.$charset);
	}

	// 发送query查询
	public function query($sql)
	{
		return $this->link->query($sql);
	}

	// 获取单条数据
	public function getRow($sql)
	{
		$res = $this->query($sql);
		return $res->fetch_assoc();
	}

	// 获取单个字段数据 count(*)
	public function getOne($sql)
	{
		$res = $this->query($sql);
		return $res->fetch_row()[0];
	}

	// 获取多条数据
	public function getAll($sql)
	{
		$data = [];
		$res = $this->query($sql);
		while ($row = $res->fetch_assoc()) {
			$data[] = $row;
		}
		return $data;
	}

	// 插入
	public function insert($table,$data)
	{
		$keys = $values = '';
		foreach($data as $k=>$v){
			$keys[] = $k;
			$values[] = $v;
		}
		$keys = implode(',',$keys);
		$values = implode(',',$values);

		$sql = "Insert into $table ($keys) values ($values)";
		$this->query($sql);
		return $this->getAffectRows();
	}

	// 修改
	public function update($table,$data,$where)
	{
		$str = '';
        foreach($data as $k=>$v){
         	$str .= "$k='$v',";
        }
        $str=rtrim($str,',');
        
        $sql="update $table set $str where $where";
        $this->query($sql);
        //返回受影响的行数
        return $this->getAffectRows();
	}

	// 删除单条
	public function deleteOne($table,$where)
	{
		if(is_array($where)){
            foreach ($where as $key => $val) {
                $condition = $key.'='.$val;
            }
        } else {
            $condition = $where;
        }
        $sql = "delete from $table where $condition";
        $this->query($sql);

        return $this->getAffectRows();
	}

	// 删除多条
	public function deleteAll($table,$where)
	{
		if( is_array($where) ){
            foreach ($where as $key => $val) {
              	if(is_array($val)){
                	$condition = $key.' in ('.implode(',', $val) .')';
              	} else {
                	$condition = $key. '=' .$val;
              	}
            }
		} else {
			$condition = $where;
		}

		$sql = "delete from $table where $condition";
		$this->query($sql);
		
		return $this->getAffectRows();
	}
	// 获取最近插入的ID
	public function getLastId()
	{
		return $this->link->insert_id;
	}

	// 获取受影响行数
	public function getAffectRows()
	{
		return $this->link->affected_rows;
	}

	// 禁止克隆
	private function __clone()
	{
		die('clone is not allowed');
	}
}