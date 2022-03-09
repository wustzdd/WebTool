<?php
namespace WebTool\DB;

/**
 * 有关PDO操作
 * usage:	$dsn = 'mysql:dbname=test;host=127.0.0.1;charset=UTF8';
			$user = 'root';$pwd = '';
			$db = PdoDB::getInstance($dsn);
			//todo
 */
class PdoDB
{

	private $errno, $errmsg; //错误信息

	private static $instance; //实例

	private $db;

	private $dsn, $user='root', $pwd='', $options=[];

	// 初始化
	private function __construct($dsn, $user='', $pwd='', $options=[])
	{
		if(!$dsn){
			trigger_error('DSN配置错误',E_USER_ERROR);
		}

		$this->dsn = $dsn;
		$user && $this->user= $user;
		$pwd && $this->pwd = $pwd;
		$options && $this->options = $options;
		//建立数据库连接
		return $this->connect();
	}

	// 连接
	private function connect()
	{
		try {
			$this->db = new PDO($this->dsn, $this->user, $this->pwd, $this->options); 
		} catch (PDOException $e) {
			trigger_error('数据库连接失败. '.$e->getMessage(),E_USER_ERROR);
		}
	}

	// 单例模式
	public function getInstance(...$params)
	{
		if( !(self::$instance instanceof self) ){
			self::$instance = new self(...$params);
		}
		return self::$instance;
	}

	// 克隆
	private function __clone()
	{
		trigger_error('禁止克隆',E_USER_ERROR);
	}

	// 获取错误号
	public function getErrno()
	{
		return $this->errno;
	}

	// 获取错误信息
	public function getErrmsg()
	{
		return $this->errmsg;
	}

	// 设置错误信息
	private function setError()
	{
		$this->errno = $this->db->errorCode();
		$this->errmsg= print_r($this->db->errorInfo(),true);
	}

	// 执行查询
	private function query($sql,$type='All')
	{

		$res = $this->db->query($sql);
		if(false === $res){
			$this->setError();
			return false;
		}
		$res->setFetchMode(PDO::FETCH_ASSOC);

		return $type == 'All' ? $res->fetchAll() : $res->fetch();
	}

	// 执行新增,修改,删除
	private function execute($sql)
	{
		if(false === $this->db->exec($sql) ){
			$this->setError();
			return false;
		}
		return true;
	}

	// 获取影响行数
	private function getInsertId()
	{
		return $this->db->lastInsertId();
	}

	// 获取全部数据
	public function findAll($sql)
	{
		return $this->query($sql);
	}

	// 获取单条数据
	public function findOne($sql)
	{
		$data = $this->query($sql,'One');
		return $data ? $data : [];
	}

	// 获取第一个字段
	public function findFirst()
	{
		$data = $this->query($sql,'One');
		return $data ? reset($data) : '';
	}

	// 新增操作
	public function insert($sql)
	{
		return $this->execute($sql);
	}

	// 新增并返回插入ID
	public function insertGetId($sql)
	{
		$ret = $this->execute($sql);
		return $ret ? $this->getInsertId() : false;
	}

	// 更新操作
	public function update()
	{
		return $this->execute($sql);
	}

	// 删除操作
	public function delete()
	{
		return $this->execute($sql);
	}
}
