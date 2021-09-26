<?php
declare(strict_types=1);

require './vendor/autoload.php';
use DfZl\Wordchecker\DFA;

$dfa = new DFA();

$startTime = formatTime(microtime(true));
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 开始程序\r\n";

if (1) {
    $keywordsTimes = 10000;
    for ($i = 0; $i < $keywordsTimes; $i++) {
        $dfa->add(getRandKeyword());
        if ($i % 100 == 0) {
            echo '.';
        }
    }
    echo "\r\n";
} else {
    $keywords = require __DIR__.'./keywords.php';

    $keywords_count = count($keywords);
    $startTime = formatTime(microtime(true));
    $memory = memory_get_usage();
    echo "{$startTime} 占用内存： {$memory} 关键词总：{$keywords_count} \r\n";

    foreach ($keywords as $v) {
        $dfa->add($v);
    }

    unset($keywords);
}

$startTime1 = formatTime(microtime(true));
$gotime = ($startTime1 - $startTime) * 1000;
$startTime = $startTime1;
$memory = memory_get_usage();
echo "{$startTime} 占用内存： {$memory} 耗时： {$gotime}  初始化dfa树完成\r\n";

$filterTotalTime = 0;

$filterTimes = 1000;

for ($i = 0; $i < $filterTimes; $i++) {
    $filterWords = getRandFilterWords();

    $startTime1 = formatTime(microtime(true));
    $gotime = ($startTime1 - $startTime) * 1000;
    $startTime = $startTime1;
    $memory = memory_get_usage();
    echo "{$startTime} 占用内存： {$memory} 耗时： {$gotime}  随机生成过滤串完成 {$filterWords}\r\n";

    $searchRes = $dfa->filter($filterWords);

    $startTime1 = formatTime(microtime(true));
    $gotime = ($startTime1 - $startTime) * 1000;
    $startTime = $startTime1;
    $memory = memory_get_usage();
    echo "{$startTime} 占用内存： {$memory} 耗时： {$gotime}  过滤字符串串完成  过滤后的结果： {$searchRes}\r\n";
    $filterTotalTime += $gotime;
}
$avgTime = $filterTotalTime / $filterTimes;
echo "关键词数量： {$keywordsTimes} 过滤平均时间： {$avgTime}";


function formatTime($time) :float {
    return (float)$time;
}

function getRandFilterWords() : string {
    
    $wordlen = rand(2, 50);
    $ci = "";
    for ($j = 0; $j < $wordlen; $j++) {
        $line = rand(0, 6762);
        $ci = $ci . getWordBydat($line);
    }
    return $ci;
}


function getRandKeyword() : string {

    $wordlen = rand(2, 5);
    $ci = "";
    for ($j = 0; $j < $wordlen; $j++) {
        $line = rand(0, 6762);
        $ci = $ci . getWordBydat($line);
    }
    return $ci;
}

function getWordBydat(int $line) : string {
    $filename = __DIR__."/pinyin-utf8.dat";
    $handle = fopen($filename, 'r');
    if ($handle) {
        $i = 0;
        while (($buffer = fgets($handle, 4096)) !== false) {
            if ($i == $line) {
                $lineArr = explode('`', trim($buffer));
                return $lineArr[0];
            }
            $i++;
        }
        if (!feof($handle)) {
            echo "\n";
            throw new Exception("Error: unexpected fgets() fail", 1);
        }
        fclose($handle);
    }
    throw new Exception("open file error", 1);
}