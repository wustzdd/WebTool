<?php
namespace WebTool\Page;
/**
*A pagination generation class
*@class  : Pagination
*@useage:
*     $pagi  =  new Pagination($url_prefix, $page_size, $mesgs_count, $pagination_size=10, array $conf, $url_suffix, $text);
*  $navigation_str = $pagi->generate($current_page_number);
*/
class Pagination
{

    private $page, $total_page, $total, $page_size, $size;
    private $prev_str = "上一页", $next_str = "下一页";
    private $prev_index = "首页", $prev_end = "尾页";
    // private $prev_str = "", $next_str = "";
    private $class, $selected_class = "selected on", $prev_class="prev", $next_class="next",
    $de_prev_class="de_prev", $de_next_class="de_next";
    private $url_prefix="", $split_char="?";
    private $para_name = "page", $target = "";
    private $url_suffix, $text;
    public function __construct($url_prefix, $page_size, $total, $size=10, $conf=array(), $url_suffix='', $text='')
    {
            $this->page       = 1;
            $this->page_size  = $page_size;
            $this->total      = $total;
            $this->total_page = intval(ceil($total/$page_size));
            $this->size       = $size;
            if(!empty($conf)){
                    // $configure = array("prev_str", "next_str", "class", "selected_class");
                    $configure = array("prev_str", "next_str", "class", "selected_class","prev_index","prev_end");
                    foreach($conf as $key => $val){
                            if(in_array($key, $configure)){
                                    $this->$val = $val;
                            }
                    }
            }
            $this->url_prefix = $url_prefix;
            if(strstr($url_prefix, '?') !== false){
                    $this->url_prefix .= "&" . $this->para_name . "=";
            }else{
                    $this->url_prefix .= "?" . $this->para_name . "=";
            }
            $this->url_suffix = $url_suffix;
            $this->text = $text;
    }

    public function generate($page){
            $this->page = $page;
            if(isset($this->page[$page])){
                    return $this->page_str[$page];
            }
            $page_start = 1;
            $half       = intval($this->size/2);
            $page_start = max(1, $page - $half);
            $page_end   = min($page_start + $this->size - 1, $this->total_page);
            $page_start = max(1, $page_end - $this->size + 1);
            $this->page_str[$page] = $this->build_nav_str($page_start, $page_end);
            return $this->page_str[$page];
    }

    private function build_nav_str($page_start, $page_end){
            $page_nums = range($page_start, $page_end);
            $target    = $this->target? " target=\"{$this->target}\"" : "";
            if($this->page == 1){
                    $page_str = <<<HTML
                    <a class="{$this->de_prev_class}"> {$this->prev_str} </a>
HTML;
            }else{
                    $page     = $this->page - 1;
                    $page_str = <<<HTML
                    <a href="{$this->url_prefix}1{$this->url_suffix}"{$this->target}>{$this->prev_index}</a>
                    <a href="{$this->url_prefix}{$page}{$this->url_suffix}"{$this->target}><i>[</i>{$this->prev_str}<i>]</i></a>
HTML;
            }
            foreach($page_nums as $p){
                    $page_str .= ($p == $this->page) ? <<<HTML
                    <a class="{$this->selected_class}"> <i>[</i>{$p}<i>]</i></a>
HTML
                    : <<<HTML
                    <a href="{$this->url_prefix}{$p}{$this->url_suffix}"{$this->target}><i>[</i>{$p}<i>]</i></a>
HTML;

            }

            if($this->page == $this->total_page){
                    $page_str .= <<<HTML
                    <a class="{$this->de_next_class}">  {$this->next_str} </a>
HTML;
            }else{
                    $page      = $this->page + 1;
                    $page_str .= <<<HTML
                    <a href="{$this->url_prefix}{$page}{$this->url_suffix}"{$this->target}>{$this->next_str}</a>
                    <a href="{$this->url_prefix}{$this->total_page}{$this->url_suffix}"{$this->target}>{$this->prev_end}</a>
HTML;
            }
            
            return $page_str.$this->text;
    }

    public function tidy_str(){
    }

    public function __call($func_name, $arguments){
            if(isset($this->$func_name)){
                    return $this->$func_name;
            }
    }

    public function __destruct(){
            unset($this->page_str);
            unset($this);
    }
}