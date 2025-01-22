<?php

class DerivationTree {
    private ?Node $root = null; 
    private array $stack = []; 

    public function pushTerminal(Token $terminal): void {
        $this->stack[] = $terminal;
    }

    public function reduce(string $nonTerminal, int $productionLength): void {
        $children = array_splice($this->stack, -$productionLength);
        $newNode = new Node($nonTerminal, $children);
        if ($this->root === null) {
            $this->root = $newNode;
        }
        $this->stack[] = $newNode;
    }

    public function getStack() {
        return $this->stack;
    }
}
