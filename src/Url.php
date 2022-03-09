<?php 
namespace WebTool;
/**
 * URL相关操作
 * X-Wolf
 * 2019-1-3
 */
class Url
{

	/**
	 * 地址跳转
	 * @param  string  $url  跳转地址
	 * @param  integer $time 延迟时间
	 * @param  string  $msg  提示信息
	 */
	static function redirect($url, $time=0, $msg='')
	{
		$url = str_replace(["\n", "\r"], '', $url);

	    if (empty($msg))
	        $msg = "系统将在{$time}秒之后自动跳转到{$url}！";

	    if ( !headers_sent() ) {
	        if (0 === $time) {
	            header('Location: ' . $url);
	        } else {
	            header("refresh:{$time};url={$url}");
	            echo($msg);
	        }
	        exit();
	    } else {
	        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
	        if ($time !== 0) $str .= $msg;
	        exit($str);
	    }
	}


}