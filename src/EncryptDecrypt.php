<?php 
namespace WebTool;
/**
 * 常用加解密方法
 * X-Wolf
 * 2019-1-12
 */
class EncryptAndDecrypt
{

	 /**
     * 加解密算法
     * @param  string $string 加密数据
     * @param  string $rand   加密随机字符串
     * @param  string $action 加解密方式标识
     * @return string         加解密之后数据
     */
    public static function mymd5($string, $rand='randstring', $action="EN") 
    {
        $secret_string = $rand.'5*a,.^&;?.%#@!'; 

        if($string=="")
            return ""; 
        if($action=="EN"){
            $md5code=substr(md5($string),8,10); 
        }else{ 
            $md5code=substr($string,-10); 
            $string=substr($string,0,strlen($string)-10); 
        } 
        //$key = md5($md5code.$_SERVER["HTTP_USER_AGENT"].$secret_string);
        $key = md5($md5code.$secret_string); 
        $string = ($action=="EN" ? $string : base64_decode($string));
        $len = strlen($key); 
        $code = ""; 
        for($i=0; $i<strlen($string); $i++){ 
            $k = $i%$len; 
            $code .= $string[$i]^$key[$k]; 
        } 
        $code = $action == "DE" ? (substr(md5($code),8,10) == $md5code ? $code : NULL) : base64_encode($code)."$md5code";

        return $code; 
    }

    // base64_encode
    public static function b64encode( $string ) {
        $data = base64_encode( $string );
        $data = str_replace( array ( '+' , '/' , '=' ), array ( '-' , '_' , '' ), $data );
        return $data;
    }
    // base64_decode
    public static function b64decode( $string ) {
        $data = str_replace( array ( '-' , '_' ), array ( '+' , '/' ), $string );
        $mod4 = strlen( $data ) % 4;
        if ( $mod4 ) {
            $data .= substr( '====', $mod4 );
        }
        return base64_decode( $data );
    }

}