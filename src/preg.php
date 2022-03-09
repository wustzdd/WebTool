<?php  
namespace WebTool;
/**
 * 正则
 * X-Wolf
 * 2019-5-16
 */
class Preg
{
	// 获取子域名
	static function subDomain(string $domain):string
	{
		preg_match('/(.*\.)?\w+\.\w+$/', $domain, $matches);
		return isset($matches[1]) ? trim($matches[1],'.') : '';
	}
}


