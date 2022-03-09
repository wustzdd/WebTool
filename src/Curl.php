<?php
namespace WebTool;

/**
 * CURL相关操作(基本curl操作 + 重试机制)
 * X-Wolf
 * 2019-1-27		
 */
class Curl
{
	private static $ch = null; 

	// 默认配置
	const DEFAULT_OPTION = [
		CURLOPT_HEADER 			=> false,
        CURLOPT_RETURNTRANSFER 	=> true,
        CURLOPT_TIMEOUT			=> 10,
        CURLOPT_CONNECTTIMEOUT  => 30
	];

	/**
	 * HTTP请求
	 * @param  string $url    请求地址
	 * @param  string $method 请求方式(get/post)
	 * @param  array  $data   数据
	 * @param  array  $params 参数
	 * @return array          结果
	 */
	static function http($url,$method,$data,$params=[])
	{
		$methods = ['post','get'];
		if( in_array($method,$methods,true) ){
			$methodName = 'http' . ucfirst($method);
			return self::$methodName($url,$data,$params);
		}
		return '请求方式暂不支持:' . $method;
	}
	/**
	 * post请求
	 * @param  string $url    请求地址
	 * @param  array  $data   数据
	 * @param  array  $params 参数
	 * @return array          返回值[结果数据,错误码]
	 */
	static function httpPost($url,$data,$params=[])
	{
		self::init();
		$params[CURLOPT_URL]  = $url;
		$params[CURLOPT_POST] = true;
		if($data){
			$params[CURLOPT_POSTFIELDS] = is_array($data) ? http_build_query($data) : $data;
		}
		
		$options = self::mergeOption(self::DEFAULT_OPTION,$params);
		return self::response($options);
	}

	/**
	 * get请求
	 * @param  string $url    请求地址
	 * @param  array  $data   数据
	 * @param  array  $params 参数
	 * @return mixed          结果
	 */
	static function httpGet($url,$data,$params=[])
	{
		self::init();
		$url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($data);
		$params[CURLOPT_URL] = $url;
		$options = self::mergeOption(self::DEFAULT_OPTION,$params);
		return self::response($options);
	}

	/**
	 * 文件上传
	 * @param  string $url      请求地址
	 * @param  string $filepath 上传文件路径
	 * @param  array  $data     数据
	 * @param  array  $params   参数
	 */
	static function httpUpload($url,$filepath,$data,$params=[])
	{
		self::init();
		$params[CURLOPT_URL]  = $url;
		$params[CURLOPT_POST] = true;
		if( class_exists('\CURLFile') ){
			$params[CURLOPT_SAFE_UPLOAD] = true;
			$content = ['file'=> new \CURLFile(realpath($filepath) )];
		}else{
			if( defined('CURLOPT_SAFE_UPLOAD') ){
				$params[CURLOPT_SAFE_UPLOAD] = false;
			}
			$content = ['file'=> '@' . realpath($filepath)];
		}

		$params[CURLOPT_POSTFIELDS] = $content;	
		$options = self::mergeOption(self::DEFAULT_OPTION,$params);
		return self::response($options);
	}

	// 初始化
	private static function init()
	{
		self::$ch = curl_init();
	}

	// 响应
	private static function response($options)
	{
		curl_setopt_array(self::$ch,$options);
		$result   = curl_exec(self::$ch);
		$httpCode = curl_getinfo(self::$ch,CURLINFO_HTTP_CODE);
		$errno    = curl_errno(self::$ch);
		if($errno || $httpCode < 200 || $httpCode >= 300){
			$error = curl_error(self::$ch);
			curl_close(self::$ch);
			return [null,$errno,$error,$httpCode];

		}
		curl_close(self::$ch);
		return [$result,null];
	}

	// 合并选项
	private static function mergeOption($defaultConfig,$customConfig)
	{
		foreach($defaultConfig as $num => $config){
			if( array_key_exists($num, $customConfig) ){
				$defaultConfig[$num] = $customConfig[$num];
				unset($customConfig[$num]);
			}
		}
		return $defaultConfig + $customConfig;
	}
}
