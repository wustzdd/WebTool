<?php    

class class_authcode{

    public  $authcode   = ''; //验证码

    private $width      = ''; //验证码图片宽

    private $height     = ''; //验证码图片高

    private $len        = ''; //验证码长度

    private $tilt       = array(-30,30);//验证码倾斜角度

    private $font       = 'AlteHaasGroteskBold.ttf';//字体文件

    private $str        = ''; //验证码基

    private $im         = ''; //生成图片的句柄

    //构造函数
    function __construct($width=100,$heigh=30,$len=4) {

        $this->width    = $width;

        $this->height   = $heigh;

        $this->len      = $len;

        $this->str      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $str_len = strlen($this->str)-1;

        for ($i=0; $i<$len; $i++) {

            $this->authcode .= $this->str[rand(0,$str_len)];

        }

    }

    //生成验证码图片
    private function imagecreate(){

        $this->im = imagecreatetruecolor($this->width,$this->height);

    }

    //干扰颜色
    private function ext_color() {

        return imagecolorallocate($this->im,rand(50, 180),rand(50, 180),rand(50, 180));

    }

    //生成干扰点
    private function ext_point() {

        for ($i=0; $i<$this->width*2; $i++) {

            imagesetpixel($this->im,rand(1,$this->width-1),rand(1,$this->height-1),$this->ext_color());

        }

    }

    //生成干扰线
    private function ext_line() {

        for ($i=0; $i<$this->len; $i++) {

            $x1 = rand(1,$this->width-1);

            $y1 = rand(1,$this->height-1);

            $x2 = rand(1,$this->width-1);

            $y2 = rand(1,$this->height-1);

            imageline($this->im,$x1,$y1,$x2,$y2,$this->ext_color());

        }

    }

    //把验证码写入图片（不能和$this->imgstrfloat()同时使用）
    private function imgstr() {

        $old_x = 1;

        for ($i=0; $i<$this->len; $i++) {

            $fontsize = rand(2,5);      //字体大小

            $tmp_1 = $fontsize*2.5;

            $tmp_2 = $i>0 ? $tmp_1 : 0;

            $y = rand(1,$this->height/2);

            $x = rand($old_x+$tmp_2, ($i+1)*($this->width)/$this->len-$tmp_1);

            $old_x = $x;

            $color = imagecolorallocate($this->im,rand(200, 255),rand(200, 255),rand(200, 255));

            imagestring($this->im,$fontsize,$x,$y,$this->authcode[$i],$color);

        }

    }

    //把验证码倾斜写入图片（注意这里不能和$this->imgstr()方法同时使用）
     private function imgstrfloat() {

        $old_x = 1;

        for ($i=0; $i<$this->len; $i++) {

            $fontfloat = rand($this->tilt[0],$this->tilt[1]);

            $fontsize = rand(10,15);        //字体大小

            $tmp_1 = $i>0 ? $fontsize : 0;

            $y = rand($fontsize+2, $this->height-2);

            $x = rand($old_x+$tmp_1+2, ($i+1)*($this->width)/$this->len-$fontsize-2);

            $old_x = $x;

            $color = imagecolorallocate($this->im, rand(200, 255), rand(200, 255), rand(200, 255));

            imagettftext($this->im, $fontsize, $fontfloat, $x, $y, $color, $this->font, $this->authcode[$i]);

        }

    }

    //输出验证码图片
    function output() {

        $this->imagecreate();

        $this->imgstr();

        //$this->imgstrfloat();

        $this->ext_point();

        $this->ext_line();

        header('content-type:image/png');

        imagepng($this->im);

        imagedestroy($this->im);

    }

}

$obj = new class_authcode();//实例化对象，并设置验证码图片的宽、高和验证码的长度

$obj->authcode; //获取验证码

$obj->output();