<?php
namespace WebTool;
/**
 * 字符串相关处理
 * X-Wolf
 * 2019-1-3
 */
class String
{
	// 生成随机字符串
	static function generateRandString($length)
	{
		if ($length > 32) $length = 32;
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charsLen = strlen($chars) - 1;
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, $charsLen)];
        }
        return $password;
	} 

	/**
	 * 字符转换
	 * @param  string $content 被转换的内容
	 * @param  string $from    源字符集
	 * @param  string $to      目标字符集
	 * @return string          转换之后内容
	 */
	static function convertCharset(String $content, $from='gbk', $to='utf-8')
	{
	    if (function_exists('mb_convert_encoding'))
	    {
	        return mb_convert_encoding($content, $to, $from);
	    }
	    elseif (function_exists('iconv'))
	    {
	        return iconv($from, $to, $content); //IGNORE 不合格就丢弃  //TRANSLIT   不合格使用相近
	    }
	    else
	    {
	        return $content;
	    }
	}

	/**
	 * 生成唯一标识 
	 */
	static function generateUniqueId()
	{
		return md5(uniqid(md5(microtime(true)),true));
	}

}