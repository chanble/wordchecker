<?php

use DfZl\Wordchecker\DFA;
use PHPUnit\Framework\TestCase;

class DFATest extends TestCase {

    public function testNew() {
        $dfa = new DFA();
        $this->assertInstanceOf(DFA::class, $dfa);
    }

    public function testAdd() {
        $dfa = new DFA();

        $dfa->add("你好");
        $this->assertEquals($dfa->arrHashMap, [
            "你" => [
                '好' => [
                    'end' => true,
                ],
                'end' => false,
            ]
        ]);

        $dfa->add("你好呀");
        $this->assertEquals($dfa->arrHashMap, [
            "你" => [
                '好' => [
                    'end' => true,
                    '呀' => [
                        'end' => true,
                    ]
                ],
                'end' => false,
            ]
        ]);

        
        $dfa->add("我好呀");
        
        $this->assertEquals($dfa->arrHashMap, [
            "我" => [
                '好' => [
                    'end' => false,
                    '呀' => [
                        'end' => true,
                    ]
                ],
                'end' => false,
            ],
            "你" => [
                '好' => [
                    'end' => true,
                    '呀' => [
                        'end' => true,
                    ]
                ],
                'end' => false,
            ]
        ]);
    }

    public function testSearch() {
        
        $dfa = new DFA();

        $dfa->add("你好");
        $dfa->add("你好呀");
        $dfa->add("我好呀");

        $searchRes = $dfa->search("你好我好大家好");
        $this->assertEquals($searchRes, [0, 2]);
        $searchRes = $dfa->search("您好我好呀大家好");
        $this->assertEquals($searchRes, [2, 3]);
        $searchRes = $dfa->search("您好大家好我好呀");
        $this->assertEquals($searchRes, [5, 3]);
    }

    public function testFilter() {
        $dfa = new DFA();

        $dfa->add("你好");
        $dfa->add("你好呀");
        $dfa->add("我好呀");

        $searchRes = $dfa->filter("巨大几点睡啊金额");
        $this->assertEquals("巨大几点睡啊金额", $searchRes);
        $searchRes = $dfa->filter("");
        $this->assertEquals("", $searchRes);
        $searchRes = $dfa->filter("你好我好大家好");
        $this->assertEquals("**我好大家好", $searchRes);
        $searchRes = $dfa->filter("您好我好呀大家好");
        $this->assertEquals("您好***大家好", $searchRes);
        $searchRes = $dfa->filter("您好大家好我好不呀");
        $this->assertEquals("您好大家好我好不呀", $searchRes);
        $dfa->add("好");
        $searchRes = $dfa->filter("我好不呀");
        $this->assertEquals("我*不呀", $searchRes);
        $searchRes = $dfa->filter("您好大家好我好不呀");
        $this->assertEquals("您*大家*我*不呀", $searchRes);
    }
}
