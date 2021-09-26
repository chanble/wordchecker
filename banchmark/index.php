<?php
declare(strict_types=1);

require './vendor/autoload.php';

use DfZl\Wordchecker\DFA;
use Carbon\Carbon;

$dfa = new DFA();

$startTime = formatTime(microtime(true));
echo "{$startTime} 开始程序\r\n";

$keywords = require __DIR__.'./keywords.php';

$keywords_count = count($keywords);
$startTime = formatTime(microtime(true));
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 关键词总：{$keywords_count} \r\n";

foreach ($keywords as $v) {
    $dfa->add($v);
}

unset($keywords);

$startTime = formatTime(microtime(true));
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 初始化dfa树完成\r\n";

$searchRes = $dfa->search("第十届中央政治局委员将在2012年10月12日召开");
$startTime = formatTime(microtime(true));
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 第231个关键词搜索完成\r\n";


$searchRes = $dfa->search("吸食毒品构成犯罪吗，贩卖毒品一斤以下怎么判刑？吸食毒品会被判刑吗，贩卖毒品应该如何处罚？吸毒犯什么罪？走私毒品罪的行为方式，运输毒品和走私毒品有什么区别？贩卖毒品罪既遂与未遂的界定，贩卖毒品能取保候审吗？");
$startTime = formatTime(microtime(true));
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 第6821个关键词搜索完成\r\n";


$searchRes = $dfa->filter("吸食毒品构成犯罪吗，贩卖毒品一斤以下怎么判刑？吸食毒品会被判刑吗，贩卖毒品应该如何处罚？吸毒犯什么罪？走私毒品罪的行为方式，运输毒品和走私毒品有什么区别？贩卖毒品罪既遂与未遂的界定，贩卖毒品能取保候审吗？");
$startTime = formatTime(microtime(true));
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 第6821个关键词过滤完成， 过滤后的结果： {$searchRes}\r\n";

$searchRes = $dfa->search("2月10日报道 港媒称，香港学生北上求学热门地的广州刮起大学生“援交风”，当地传媒披露网络涌现自称大学生“援交女”，酒店援交透过微博发放性感照或影片，再经微信等平台与“客户”讨价还价，由数百元至数千元(人民币.下同)不等。由于广州学生群体感染艾滋病个案持续上升，专家担忧“援交风”气恐令艾滋病扩散。");
$startTime = formatTime(microtime(true));
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 第14543个关键词搜索完成\r\n";

function formatTime($time) :string {
    return (string)$time;
}