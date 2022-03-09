<?php
/*
抽象工厂：提供一个创建一系列相关或相互依赖对象的接口。 
注意：这里和工厂方法的区别是：一系列，而工厂方法则是一个。
那么，我们是否就可以想到在接口create里再增加创建“一系列”对象的方法呢？
*/
interface  people {
    function  jiehun();
}
class Oman implements people{
    function jiehun() {
        echo '美女，我送你玫瑰和戒指！<br>';
    }
}
class Iman implements people{
    function jiehun() {
        echo '我偷偷喜欢你<br>';
    }
}
 
class Owomen implements people {
    function jiehun() {
        echo '我要穿婚纱！<br>';
    }
}
 
class Iwomen implements people {
    function jiehun() {
        echo '我好害羞哦！！<br>';
    }
}
 
interface  createMan {  // 注意了，这里是本质区别所在，将对象的创建抽象成一个接口。
    function createOpen(); //分为 内敛的和外向的
    function createIntro(); //内向
 
}
class FactoryMan implements createMan{
    function createOpen() {
        return  new  Oman;
    }
    function createIntro() {
        return  new Iman;
    }
}
class FactoryWomen implements createMan {
    function createOpen() {
        return  new  Owomen;
    }
    function createIntro() {
        return  new Iwomen;
    }
}
 
class  Client {
    // 简单工厂里的静态方法
    function test() {
        $Factory =  new  FactoryMan;
        $man = $Factory->createOpen();
        $man->jiehun();
        
        $man = $Factory->createIntro();
        $man->jiehun();
        
        
        $Factory =  new  FactoryWomen;
        $man = $Factory->createOpen();
        $man->jiehun();
        
        $man = $Factory->createIntro();
        $man->jiehun();
        
    }
}
 
$f = new Client;
$f->test();