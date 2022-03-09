<?php
namespace WebTool;
/**
 * session相关操作
 * X-Wolf
 * 2019-1-3
 */
class Session
{


	// 激活session
	static function doActive()
	{
		session_status() === PHP_SESSION_ACTIVE OR session_start();
	}

	/**
	 * session操作
	 * @param  string $name  key名
	 * @param  string $value key值
	 * @return mixed         操作结果
	 */
	static function cache(String $name,$value='')
	{
		if($name){
	        if($value === NULL){
	            if(isset($_SESSION[$name])) unset($_SESSION[$name]);
	            return true; 
	        }
	        elseif($value === ''){
	            if(isset($_SESSION[$name])) return $_SESSION[$name];
	        }else{
	            $_SESSION[$name] = is_scalar($value) ? $value : json_encode($value);
	            return true;
	        }
	    }
	    return false;
	}
}