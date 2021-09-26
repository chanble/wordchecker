<?php
declare(strict_types=1);

namespace DfZl\Wordchecker;

class DFA
{
    private $arrHashMap = [];

    public function addKeyWord($strWord) {
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
    }
 
    public function searchKey($strWord) {
        $len = mb_strlen($strWord, 'UTF-8');
        $arrHashMap = $this->arrHashMap;
        for ($i=0; $i < $len; $i++) {
            $word = mb_substr($strWord, $i, 1, 'UTF-8');
            if (!isset($arrHashMap[$word])) {
                // reset hashmap
                $arrHashMap = $this->arrHashMap;
                continue;
            }
            if (isset($arrHashMap[$word]['end']) && $arrHashMap[$word]['end']) {
                return true;
            }
            $arrHashMap = $arrHashMap[$word];
        }
        return false;
    }
}