<?php
declare(strict_types=1);

namespace DfZl\Wordchecker;


class Node {
    /**
     * @var Node $next
     */
    private $next;
}

/**
 * Aho-Corasick
 */
class AC implements CheckerInterface
{
    
    public function add(string $strWord) : Self
    {

        return $this;
    }
    public function search(string $strWord) : array
    {

        return [];
    }
    public function filter(string $strWord) : string
    {

        return "";
    }

    
    public function clear() : Self
    {
        return $this;
    }
}