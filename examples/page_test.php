<?php 
use WebTool\Page\Pagination;

$total = 100;
$page = 3;
$limit = 10;
$currentUrl = '/article/list';
$text = "<span id='page'>{$page}/".ceil($total/$limit)."页;每页{$limit}条; 共{$total}条记录.</span>";
$pager = new Pagination($url, $limit, $total, 10, [], '', $text);
$pagestr = $pager->generate($page);