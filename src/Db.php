<?php 
namespace WebTool;

/**
 * 数据库相关操作
 */
class Db
{

	/**
	 * 获取分表
	 * @param  string  $table 表前缀
	 * @param  string  $code  字符串
	 * @param  integer $size  表数量
	 * @return string         表名
	 */
	function get_hash_table($table,$code,$size=100)
	{
	    $hash = sprintf("%u", crc32($code));
	    $hash1 = intval(fmod($hash, $size));
	    return $table."_".$hash1;
	}
}