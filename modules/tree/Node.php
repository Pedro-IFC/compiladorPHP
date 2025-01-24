<?php 

class Node {
    public string $symbol;
    public array $children;

    public function __construct(string $symbol, array $children = []) {
        $this->symbol = $symbol;
        $this->children = $children;
    }

}
