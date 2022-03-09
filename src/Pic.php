<?php
namespace WebTool;
/**
 * 图片相关操作
 * X-Wolf
 * 2019-1-12
 */
class Pic
{

	// 将图片base64处理
    static function toBase64($img) 
    {
        $img_base64 = '';
        if (file_exists($img)) {
            $img_info = getimagesize($img);
            if ($img_info[2] === 2 || $img_info[2] === 3) {
                $fp = fopen($img, "rb");
                if ($fp) {
                    $filesize = filesize($img);
                    $content = fread($fp, $filesize);
                    $img_base64 = chunk_split(base64_encode($content));
                }
                fclose($fp);
            }
        }
        return $img_base64;
    }
}