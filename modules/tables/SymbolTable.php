<?php

class SymbolTable {
    private $symbols = [];
    private $scopeStack = [];

    public function enterScope() {
        array_push($this->scopeStack, []);
    }

    public function exitScope() {
        array_pop($this->scopeStack);
    }

    public function addSymbol($name, $type) {
        $currentScope = &$this->scopeStack[count($this->scopeStack) - 1];
        if (isset($currentScope[$name])) {
            throw new Exception("Erro semântico: Variável '$name' já declarada neste escopo.");
        }
        $currentScope[$name] = $type;
    }

    public function getSymbol($name) {
        for ($i = count($this->scopeStack) - 1; $i >= 0; $i--) {
            if (isset($this->scopeStack[$i][$name])) {
                return $this->scopeStack[$i][$name];
            }
        }
        throw new Exception("Erro semântico: Variável '$name' não declarada.");
    }
}
