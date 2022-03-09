<?php
namespace WebTool;
/**
 * 数组相关操作
 * X-Wolf
 * 2019-1-3
 */
class Arr
{

	//将对象转成数组
	static function objectToArray(object $object)
	{
		if(!is_array($object) && !is_object($object)){
			return $object;
		}
		if( is_object($object) ){
			$object = get_object_vars($object);
		}
		return array_map(['Arr','objectToArray'],$object);
	}

	//计算数组总和(支持多维)
	static function arraySum(array $array)
	{
		$total = 0;
		foreach(new recursiveIteratorIterator( new recuriveArrayIterator($array) ) as $num){
			$total += $num;
		}
		return $total;
	}

	// 将多维数组转化成一维数组
	static function reduceArray($array) {
	    $return = [];
	    array_walk_recursive($array, function ($x) use (&$return) {
	        $return[] = $x;
	    });
	    return $return;
	}

	/**
	 * 生成一定数量的不重复随机数
	 * @param  integer $min 最小值
	 * @param  integer $max 最大值
	 * @param  integer $num 随机数数量
	 * @return array        返回值
	 */
	static function generateUniqueRand($min,$max,$num)
	{
		$count = 0;
	    $return = [];
	    while ($count < $num) {
	        $return[] = mt_rand($min, $max);
	        $return   = array_flip(array_flip($return));
	        $count    = count($return);
	    }
	    shuffle($return);
	    return $return;
	}

}