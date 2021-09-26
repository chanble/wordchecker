<?php
declare(strict_types=1);

namespace DfZl\Wordchecker;

class DFA
{
    private $arrHashMap = [];

    public function __get($name) {
        return $this->$name;
    }

    /**
     * 添加关键词
     * @param string $strWord 要添加的关键词
     * @return DFA 返回this  支持链式添加
     */
    public function add($strWord) {
        $len = mb_strlen($strWord, 'UTF-8');

        // 传址
        $arrHashMap = &$this->arrHashMap;
        for ($i=0; $i < $len; $i++) {
            $word = mb_substr($strWord, $i, 1, 'UTF-8');
            // 已存在
            if (isset($arrHashMap[$word])) {
                if ($i == ($len - 1)) {
                    $arrHashMap[$word]['end'] = true;
                }
            } else {
                // 不存在
                if ($i == ($len - 1)) {
                    $arrHashMap[$word] = [];
                    $arrHashMap[$word]['end'] = true;
                } else {
                    $arrHashMap[$word] = [];
                    $arrHashMap[$word]['end'] = false;
                }
            }
            // 传址
            $arrHashMap = &$arrHashMap[$word];
        }
        return $this;
    }

    /**
     * 判断给定的字符串($strWord)中是否包含关键词
     * @param string $strWord 要搜索的字符串
     * @return array 长度为2的数组 0表示字符串的开始位置， 1表示关键词长度
     */
    public function search($strWord) : array {
        $len = mb_strlen($strWord, 'UTF-8');
        $arrHashMap = $this->arrHashMap;
        // 匹配的开始位置
        $start = -1;
        $keywordLen = 0;
        for ($i=0; $i < $len; $i++) {
            $word = mb_substr($strWord, $i, 1, 'UTF-8');
            if (!isset($arrHashMap[$word])) {
                // reset hashmap
                $arrHashMap = $this->arrHashMap;
                $keywordLen = 0;
                continue;
            }
            $keywordLen += 1;
            if (isset($arrHashMap[$word]['end']) && $arrHashMap[$word]['end']) {
                return [$start, $keywordLen];
            } else {
                // 记录关键词匹配的最初位置
                if ($start < 0) {
                    $start = $i;
                }
            }
            $arrHashMap = $arrHashMap[$word];
        }
        return [];
    }
}