<?php 
namespace WebTool;
/**
 * 时间日期验证
 * X-Wolf
 * 2019-3-27
 */
class T
{
	/**
     * 获取指定月份始末时间戳
     * @param  integer $year  年份
     * @param  integer $month 月份
     */
    function getMonthTimestamps($year,$month)
    {
        if( !checkdate($month, 1, $year) ){
            return false;
        }
        
        $start = mktime(0, 0, 0, $month, 1, $year); 
        $end   = mktime(0, 0, 0, $month, date('t',$start), $year);
        return $start && $end ? [$start,$end] : false;
    }

    /**
     * 验证日期
     * @param  string 日期
     * @param  bool   整点验证
     */
    function validateDate($date,$isZero=false)
    {
        $timestamp = strtotime($date);
        if(false === $timestamp){
            return false;
        }
        if($isZero){
            $today = strtotime('today');
            $interval = abs($timestamp - $today);
            if( !is_int($interval/(24*60*60) ) ){
                return false;
            }
        }
        return $timestamp;
    }
}