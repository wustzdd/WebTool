<?php
namespace WebTool;
/**
 * 常用方法
 * X-Wolf
 * 2019-1-3
 */
class Common
{
	/**
     * 验证手机号
     * 13[0-9],14[5,7],15[0,1,2,3,5,6,7,8,9],17[6,7,8],18[0-9],170[0-9]
     * 移动号段: 134,135,136,137,138,139,150,151,152,157,158,159,182,183,184,187,188,147,178,1705
     * 联通号段: 130,131,132,155,156,185,186,145,176,1709
     * 电信号段: 133,153,180,181,189,177,1700
     */
    public static function checkMobile($mobile)
    {
        if(preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|8[0-9]|7[0-9])\\d{8}$/', $mobile)){
            return 1;
        }
        elseif(preg_match('/(^1(3[4-9]|4[7]|5[0-27-9]|7[8]|8[2-478])\\d{8}$)|(^1705\\d{7}$)/', $mobile)){
            return 2;
        }
        elseif(preg_match('/(^1(3[0-2]|4[5]|5[56]|7[6]|8[56])\\d{8}$)|(^1709\\d{7}$)/', $mobile)){
            return 3;
        }
        elseif(preg_match('/(^1(33|53|77|8[019])\\d{8}$)|(^1700\\d{7}$)/', $mobile)){
            return 4;
        }else{
            return 0;
        }
    }

    // 过滤掉emoji表情  
    function filterEmoji($str)  
    {  
        $str = preg_replace_callback(  
        '/./u',  
        function (array $match) {  
            return strlen($match[0]) >= 4 ? '' : $match[0];  
        },  
        $str);  
      
      return $str;  
    } 
  
}