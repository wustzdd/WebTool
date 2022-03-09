<?php 
namespace WebTool;
/**
 * 文件相关处理工具
 * X-Wolf
 * 2018-8-29 
 */
class File
{
	/**
     * 获取二进制流的文件类型
     * @param  String $stream 二进制流
     * @return String $type   文件类型
     */
    function getStreamType($stream)
    {
        if( empty($stream) ) return;

        $bin = substr($stream, 0,2);
        $code = @unpack('C2chars', $bin); //将二进制转化为十进制
        $code = intval($code['chars1'].$code['chars2']);

        $map = [
            255216  =>  'jpg',
            13780   =>  'png',
            8297    =>  'rar',
            8273    =>  'wav',
            7798    =>  'exe',
            7784    =>  'midi',
            7368    =>  'mp3',
            7173    =>  'gif',
            6677    =>  'bmp',
            0       =>  'mp4',
        ];

        return array_key_exists($code, $map) ? $map[$code] : 'unknow';
    }

    // 计算目录文件大小
    static function dirSize($dir)
    {
        $size = 0;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file){
            $size += $file->getSize();
        }
        return $size;
    }

    /**
     * 读取文件某行内容
     * @param  string $file        文件名称
     * @param  integer $lineNumber 行号
     * @return string              指定行内容
     */
    static function readFileLine($file,$lineNumber)
    {
        $fileObj = new SplFileObject($file);
        $fileObj->seek($lineNumber);
        return $fileObj->current();
    }

    /**
     * 验证文件的存在性(本地+远程)
     * @param  string $file 文件路径
     * @return bool         是否存在
     */
    function checkFileExist($file)
    {
        if($file){
            if(stripos($file,'http') === 0){
                $header = get_headers($file,1);
                return isset($header[0]) && ( strpos($header[0],'200') || strpos($header[0],'304') ) && stripos($header[0],'OK');
            }else{
                if( existChinese($file) ){
                    $file = iconv('UTF-8', 'GBK', $file);
                }
                return file_exists($file);
            }
        }
        return false;
    }

}
