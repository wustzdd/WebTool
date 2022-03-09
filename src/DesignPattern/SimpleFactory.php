<?php 

/**
 *简单工厂又叫静态工厂方法模式，这样理解可以确定，简单工厂模式是通过一个静态方法创建对象的。
 */
interface  people {
    function  jiehun();
}
class man implements people{
    function jiehun() {
        echo '送玫瑰，送戒指！<br>';
    }
}
  
class women implements people {
    function jiehun() {
        echo '穿婚纱！<br>';
    }
}
  
class SimpleFactoty {
    // 简单工厂里的静态方法
    static function createMan() {
        return new     man;
    }
    static function createWomen() {
        return new     women;
    }
     
}
  
$man = SimpleFactoty::createMan();
$man->jiehun();
$man = SimpleFactoty::createWomen();
$man->jiehun();