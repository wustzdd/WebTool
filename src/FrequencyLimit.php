<?php 
/**
 * 频次限制
 * X-Wolf
 * 2019-7-20
 */
class FrequencyLimit
{

	const DEFAULT_EXPIRE_TIME = 3600*24*7; //默认自动过期时间

	static $r = null;
	// 初始化Redis
	public function __construct()
	{
		// 初始化Redis
		self::$r = new Client([
			'host'	=>	\Env::get(ENV.'.redis_host'),
			'port'	=>	\Env::get(ENV.'.redis_port'),
			'database'=> 5,
		]); 
	} 

	// ip限制
	public function ip(string $ip, int $time, int $number )
	{
		$key = C::IP_LIMIT . $ip;
		return $this->do($key,$time,$number);
	}

	// 接口限制
	public function api(string $appid,string $url,int $time, int $number)
	{
		$key = C::API_LIMIT . $appid.'_'.$url;
		return $this->do($key,$time,$number);
	}

	// 频次间隔限制
	public function apiInterval(string $appid, string $url, int $interval)
	{
		$key = C::API_INTERVAL_LIMIT . $appid.'_'.$url;
		return $this->run($key,$interval);
	}

	/**
	 * 执行频次限制
	 * @param  string $key    key键名
	 * @param  int    $time   时间范围[秒]
	 * @param  int    $number 操作次数
	 * @param  array  $expire 封禁时间['type'=>1,'ttl'=>'过期时间'],['type'=>2,'ttl'=>'具体过期时间戳']
	 * @return bool   结果
	 */
	private function do($key, $time, $number, $expire=[])
	{
		$current = intval(self::$r->get($key) );
		if($current >= $number) return false;

		$current = self::$r->incr($key);
		if($current === 1) self::$r->expire($key,$time);
		if($current < $number) return true;

		if($expire){
			$type = !$expire['type'] ? 0 : intval($expire['type']);
			$ttl = !$expire['ttl'] ? 0 : intval($expire['ttl']);
			if($current === $number && $ttl > 0 && in_array($type, [1,2]) ){
				$type === 1 ? self::$r->expire($key,$ttl) :  self::$r->expireAt($key,$ttl);
			}
		}
		return false;
	}

	/**
	 * 执行时间间隔限制
	 * @param  string $key key名称
	 * @param  int    $interval 间隔时间
	 * @return bool 结果
	 */
	private function run($key,$interval)
	{
		if(!self::$r->exists($key) ){
			self::$r->setex($key,self::DEFAULT_EXPIRE_TIME,time());
			return true;
		}

		$timestamp = self::$r->get($key);
		if(time() - $timestamp > $interval){
			self::$r->setex($key,self::DEFAULT_EXPIRE_TIME,time());
			return true;
		}

		return false;
	}

}