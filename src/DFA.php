<?php
declare(strict_types=1);

namespace DfZl\Wordchecker;

class DFA
{
    const CONCEAL_CHAR = '*';
    private $arrHashMap = [];

    public function __get($name) {
        return $this->$name;
    }

    /**
     * 添加关键词
     * @param string $strWord 要添加的关键词
     * @return DFA 返回this  支持链式添加
     */
    public function add($strWord) : Self {
        $len = utf8_strlen($strWord);

        // 传址
        $arrHashMap = &$this->arrHashMap;
        for ($i=0; $i < $len; $i++) {
            $word = utf8_substr($strWord, $i, 1);
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
        $len = utf8_strlen($strWord);
        $arrHashMap = $this->arrHashMap;
        // 匹配的开始位置
        $start = -1;
        $keywordLen = 0;
        for ($i=0; $i < $len; $i++) {
            $word = utf8_substr($strWord, $i, 1);
            if (!isset($arrHashMap[$word])) {
                // reset hashmap
                $arrHashMap = $this->arrHashMap;
                $keywordLen = 0;
                // 如果一个关键词没有匹配到，应该回归到最初匹配到的字的位置
                // 如： 匹配关键词“制毒贩罪”和“毒贩”时， 如果不能匹配“制毒贩罪”，应该回归到“毒”字进行匹配，而不能继续后面的字符串
                if ($start >= 0) {
                    $i = $start;
                    $start = -1;
                }
                continue;
            }
            $keywordLen += 1;

            // 记录关键词匹配的最初位置
            if ($start < 0) {
                $start = $i;
            }
            if (isset($arrHashMap[$word]['end']) && $arrHashMap[$word]['end']) {
                return [$start, $keywordLen];
            }
            $arrHashMap = $arrHashMap[$word];
        }
        return [];
    }

    public function filter(string $strWord) : string {
        if (empty($strWord)) {
            return "";
        }
        // 如果没有匹配到关键词， 返回
        $searchRes = $this->search($strWord);
        if (count($searchRes) !== 2) {
            return $strWord;
        }
        $startStr = "";
        $remainder = "";
        // 判断匹配的字符串是否在开始位置
        if ($searchRes[0] > 0) {
            $startStr = utf8_substr($strWord, 0, $searchRes[0]);
        }
        $remainderStartPos = $searchRes[0] + $searchRes[1];
        // 判断匹配的字符串是否在结束位置
        if (utf8_strlen($strWord) > $remainderStartPos) {
            $remainder = utf8_substr($strWord, $remainderStartPos);
        }
        return $startStr 
            . str_repeat(Self::CONCEAL_CHAR, $searchRes[1])
            . $this->filter($remainder);
    }
}
