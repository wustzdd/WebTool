<?php
namespace WebTool;
/**
 * 数据验证
 * X-Wolf
 * 2019-1-12
 */
class Validate
{
	// -------------------------   环境验证   ------------------------

	static function isWechat()
	{ 
	    return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }

	// -------------------------   Session验证   ------------------------

	// session是否启用(验证)
	static function isActived()
	{
		if( !Common::isCli() ){
			if( version_compare(phpversion(), '5.4.0', '>=') ){
				return session_status() === PHP_SESSION_ACTIVE;
			}else{
				return !empty( session_id() );
			}
		}
		return false;
	}

	// -------------------------   HTTP验证   ------------------------

	// 验证是否是HTTPS协议
	static function isHttps()
	{
		return ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) 
			|| ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' );
	}

	// -------------------------   图片验证   ------------------------

	// 是否是GIF图
	static function isGif($img)
	{
		$handle = fopen($img,'rb');
	    $img = fread($handle,'1024');
	    fclose($handle);
	    return strpos($img,chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0') !== FALSE;
	}

	// -------------------------   系统验证   ------------------------

	// 是否是CLI环境(验证)
	static function isCli()
	{
		return PHP_SAPI == 'cli';
	}

	// 是否是Windows环境1
	static function isWin1()
	{
		return strncasecmp(PHP_OS,'win',3) === 0;
	}
	// 是否是Windows环境2
	static function isWin2()
	{
		return strncasecmp(php_uname('s'),'win',3) === 0;
	}
	// 是否是Windows环境3
	static function isWin3()
	{
		return DIRECTORY_SEPARATOR === chr(92);
	}
	// 是否是Windows环境4
	static function isWin4()
	{
		return PATH_SEPARATOR === chr(59);
	}
	// 是否是Windows环境5
	static function isWin5()
	{
		return strcasecmp(PHP_SHLIB_SUFFIX,'dll') === 0;
	}

	// -------------------------   数组验证   ------------------------

	// 是否是多维数组
	static function isMultidimensionalArray(Array $array)
	{
		return count($array,1) === count($array);
	}

	// -------------------------   字符串验证   ------------------------
	
	// 是否存在中文字符
	static function isExistChinese($char)
	{
		return preg_match("/[\x{4e00}-\x{9fa5}]+/u",$char); // /([\x81-\xfe][\x40-\xfe])/
	}

    // 字符串非法字符检测
    static function illegalCharacters($strOrArr)
    {
        $IllegalArr = [
        	'"','\'','\\','\/','&','||','%','*','(',')',
        	'select','update','delete','insert','create','modify',
        ];
        if(is_array($strOrArr)){
            foreach($strOrArr as $str){
                foreach($IllegalArr as $char){
                    if(strpos(strtolower($str), $char) !== false){
                        return false;
                    }
                }
            }
        }else{
            foreach($IllegalArr as $char){
                if(strpos(strtolower($strOrArr), $char) !== false){
                    return false;
                }
            }
        }
        
        return true;
    } 

	// -------------------------   文件验证   ------------------------

	// 文件是否存在(远程+本地)
	static function isExistFile($file)
	{
		if($file){
			if(stripos($file,'http') === 0){
				$header = get_headers($file,1);
				return isset($header[0]) && ( strpos($header[0],'200') || strpos($header[0],'304') ) && stripos($header[0],'OK');
			}else{
				if( self::isExistChinese($file) ){
					$file = iconv('UTF-8', 'GBK', $file);
				}
				return file_exists($file);
			}
		}
		return false;
	}

	// -------------------------   内容验证   ------------------------
	
	// 验证身份证号
    public static function isCreditNo($vStr)
    {
        $vCity = array(
            '11','12','13','14','15','21','22',
            '23','31','32','33','34','35','36',
            '37','41','42','43','44','45','46',
            '50','51','52','53','54','61','62',
            '63','64','65','71','81','82','91'
        );
     
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
     
        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
     
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
     
        if ($vLength == 18)
        {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
     
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18)
        {
            $vSum = 0;
     
            for ($i = 17 ; $i >= 0 ; $i--)
            {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
            }
     
            if($vSum % 11 != 1) return false;
        }
     
        return true;
    } 

    // 验证ip地址
    public static function ip( $str ) 
    {
        if ( empty( $str ) )
            return false;

        if ( ! preg_match( '#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $str ) ) {
            return false;
        }

        $ip_array = explode( '.', $str );

        //真实的ip地址每个数字不能大于255（0-255）
        return ( $ip_array[0] <= 255 && $ip_array[1] <= 255 && $ip_array[2] <= 255 && $ip_array[3] <= 255 ) ? true : false;
    }

    /**
     * 参数验证(仅类型)
     * @param array      $para 		数据
     * @param array      $standard 	参数要求
     * @return boolen
     * 使用:
     * 		Validate::verifyParams($data,[
                'REQUIRED' => [
                    'username'    => 'string',
                ],
                'OPTIONAL' => [
                    'ip'          => 'string',
                ],
            ]);
     */
    public static function verifyParams($para, $standard)
    {
        if ($para === false || empty($para)) {
            return false;
        }

        foreach ($standard['REQUIRED'] as $k => $v) {
            if (!array_key_exists($k, $para)) {
                return false;
            }
            if(empty($para[$k])){
                return false;
            }

            if ('string' == $v) {
                if (false === is_string($para[$k])) {
                    return false;
                }
            } else if ('int' == $v) {
                if ((string)((int)($para[$k])) != $para[$k]) {
                    return false;
                }
            } else {
                return false;
            }
        }

        foreach ($standard['OPTIONAL'] as $k => $v) {
            if (!array_key_exists($k, $para)) {
                continue;
            }

            if ('string' == $v) {
                if (!empty($para[$k]) && false === is_string($para[$k])) {
                    return false;
                }
            } else if ('int' == $v) {
                if (!empty($para[$k]) && (string)((int)($para[$k])) != $para[$k]) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

}