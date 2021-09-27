<?php
declare(strict_types=1);

namespace DfZl\Wordchecker;

interface CheckerInterface {
    
    /**
     * 添加关键词
     * @param string $strWord 要添加的关键词
     * @return DFA 返回this  支持链式添加
     */
    public function add(string $strWord) : Self;
    /**
     * 判断给定的字符串($strWord)中是否包含关键词
     * @param string $strWord 要搜索的字符串
     * @return array 长度为2的数组 0表示字符串的开始位置， 1表示关键词长度
     */
    public function search(string $strWord) : array;
    /**
     * 过滤关键词，将关键词替换成等个数的 CONCEAL_CHAR
     * @param string $strWord 要替换的字符串
     * @return string 替换后的字符串
     */
    public function filter(string $strWord) : string;
    /**
     * 清空trie树
     */
    public function clear() : Self;
}