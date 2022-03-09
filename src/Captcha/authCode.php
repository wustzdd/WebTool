<?php  

class authCode
{

    private static $instance = null;     #实例对象

    private $width = 120;                #图片宽度

    private $height = 40;                #图片高度

    private $font = 'font/elephant.ttf'; #字体文件路径

    private $fontSize = 14;              #字体大小

    private $strLen = 6;                 #字符个数

    private $auth_code_str = null;       #验证码结果

    private $imgResult = null;           #图片资源


    #入口文件 静态方法调用 实例化对象 可用 对象方法调用
    public static function img()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    
     #随机颜色
    private function randomColor($img = null, $min = 0, $max = 255)
    {
        $rgb = [];

        for ($i = 1; $i <= 3; $i++) {

            $rgb[] = str_pad(rand($min, $max), 3, 0, STR_PAD_LEFT);

        }

        return imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
    }

     
    #随机字符串
    private function randomStr($num = 4)
    {
        if ($num > 0) {

            $string = array_merge(range('a', 'z'), range(0, 9), range('A', 'Z'), range(0, 9));

            for ($i = 1; $i <= $num; $i++) {

                shuffle($string);

                $this->auth_code_str .= array_pop($string);

            }

        }

        return $this;
    }

     
    #创建验证码
    public function createAuthCode(&$codeStr = false)
    {
        if (!$this->auth_code_str) {

            $this->randomStr($this->strLen);

        }

        if ($codeStr !== false && empty($codeStr)) {

            $codeStr = $this->auth_code_str;

        } else if (!empty($codeStr) && $codeStr !== false) {

            $this->auth_code_str = $codeStr;

        }

        $this->imgResult = imagecreatetruecolor($this->width, $this->height);

        $background = $this->randomColor($this->imgResult, 200);

        imagefilledrectangle($this->imgResult, 0, 0, $this->width, $this->height, $background);

        $y = ($this->height - $this->fontSize);

        $string = str_split($this->auth_code_str, 1);

        for ($i = 0; $i < count($string); $i++) {

            $frontColor = $this->randomColor($this->imgResult, 0, 200);

            imagefttext($this->imgResult, $this->fontSize, rand(0, 10), ($this->fontSize + 2) * $i + 10, $y, $frontColor, $this->font, $string[$i]);

        }

        return $this;
    }

     
    #生成线
    public function line($line = 3)
    {
        $line = $line ?: 3;

        for ($i = 1; $i <= $line; $i++) {

            $lineColor = $this->randomColor($this->imgResult, 0, 200);

            imageline($this->imgResult, rand(0, $this->width / 5), rand(5, $this->height - 5), rand($this->width / 1.3, $this->width), rand(5, $this->height - 5), $lineColor);

        }

        return $this;
    }

     
    #噪点
    public function pixel($num = 50)
    {
        $num = $num ?: 3;

        for ($i = 1; $i <= $num; $i++) {

            $lineColor = $this->randomColor($this->imgResult, 0, 100);

            imagesetpixel($this->imgResult, rand(0, $this->width), rand(0, $this->height), $lineColor);

        }

        return $this;
    }

     

    #设置大小
    public function size($width = null, $height = null)
    {
        $this->width = $width ?: 120;

        $this->height = $height ?: 40;

        return $this;
    }

     
    #设置字体大小
    public function fontSize($fontsize = 14)
    {
        $this->fontSize = $fontsize ?: 14;

        return $this;
    }

     

    #设置字体
    public function font($file = null)
    {
        if (is_null($file) === true) {

            $this->font = 'font/elephant.ttf';

        } else {

            $this->font = $file;

        }

        return $this;
    }

     

    #设置长度
    public function strlen($num = null)
    {
        $this->strLen = $num ?: 6;

        return $this;
    }

     
    public function display()
    {
        ob_end_flush();

        header("content-type:image/jpeg");

        imagejpeg($this->imgResult, null, 100);

        imagedestroy($this->imgResult);

        exit;
    }

}

 

#简单调用方法
$code = '';
$auth = authCode::img()->createAuthCode($code);
file_put_contents('auth_code.txt', $code."\n",FILE_APPEND);
$auth->display();


#指定字符串调用

// $string = 'abc123';

// authCode::img()->createAuthCode($string)->display();

  

#设置图片大小、字数、字体大小

// authCode::img()->strlen(8)->size(300,100)->fontSize(30)->createAuthCode()->display();

  

#添加噪点

// authCode::img()->createAuthCode()->line()->pixel()->display();


























