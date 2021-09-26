DFA搜索过滤字符串
=======================================================

## 说明

用dfa算法，将关键词组成树，然后进行搜索


## 依赖
- mbstring
- 执行基准测试时，需要修改php.ini参数
```
memory_limit = 2G
```

## 用法

```php

use DfZl\Wordchecker\DFA;

$dfa = new DFA();

$dfa->add("你好");
$dfa->add("你好呀");
$dfa->add("我好呀");

$searchRes = $dfa->filter("你好我好大家好");

// **我好大家好
echo $searchRes;

```

## 执行测试
```shell
# 测试
composer run test

#基准测试
php ./banchmark/index.php
```