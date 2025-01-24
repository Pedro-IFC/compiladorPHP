<?php

class DerivationTree {
    private $stack = [];
    private $scopeStack = []; // Pilha para gerenciar escopos
    private $scopeCounter = 0; // Contador para escopos únicos

    public function __construct() {
        // Escopo global (0) no início
        $this->scopeStack[] = $this->scopeCounter;
    }

    public function pushTerminal(Token $token, $type = null, $func = false): void {
        $this->stack[] = [
            'type' => 'terminal',
            'value' => $token
        ];
    }

    public function reduce(string $nonTerminal, int $productionLength): array {
        $children = [];
        for ($i = 0; $i < $productionLength; $i++) {
            $children[] = array_pop($this->stack);
        }
        $children = array_reverse($children);

        $this->stack[] = [
            'type' => 'nonTerminal',
            'value' => $nonTerminal,
            'children' => $children
        ];

        // Gerencia escopos para não-terminais como `FUNCAO`, `SE`, `SENAO`, etc.
        $this->manageScope($nonTerminal);

        return $children;
    }

    public function getTree(): array {
        return $this->stack;
    }

    public function getScopeStack(): array {
        return $this->scopeStack;
    }

    private function manageScope(string $nonTerminal): void {
        if (in_array(strtoupper($nonTerminal), ['FUNCAO', 'SE', 'SENAO', 'ENQUANTO', 'PARA', 'CONTROLEFLUXO'])) {
            $this->scopeCounter++; // Incrementa o contador para novo escopo
        }
    }
}
