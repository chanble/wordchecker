<?php
declare(strict_types=1);

namespace DfZl\Wordchecker;

const UTF8 = 'UTF-8';

/**
 * 用utf8格式截取字符串
 * @param string $str 要截取的字符串
 * @param int $start 开始位置 从0开始
 * @param int $len 要截取的长度， 0或不传表示到结尾
 */
function utf8_substr(string $str, int $start, int $len = 0) : string {
    $mblen = $len;
    if ($len == 0) {
        $mblen = null;
    }
    return mb_substr($str, $start, $mblen, UTF8);
}

/**
 * 用utf-8格式获取字符串长度
 * @param string $str 要操作的字符串
 * @return int 字符串的长度
 */
function utf8_strlen(string $str) : int {
    return mb_strlen($str, UTF8);
}