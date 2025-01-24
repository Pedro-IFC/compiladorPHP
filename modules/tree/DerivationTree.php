<?php

class DerivationTree {
    private $stack = [];
    private $symbolTable = [];
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

        // Adiciona à tabela de símbolos se for um identificador (ID)
        if ($token->getName() === 'ID' && $type !== null) {
            $this->addToSymbolTable($token, $type, $func);
        }

        // Verifica se o token é `FC` e gerencia o fim do escopo
        if ($token->getName() === 'FC') {
            $this->endScope();
        }
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

    public function getSymbolTable(): array {
        return $this->symbolTable;
    }

    public function getScopeStack(): array {
        return $this->scopeStack;
    }

    private function addToSymbolTable(Token $token, $type, $func): void {
        $this->symbolTable[] = [
            'name' => $token->getLexeme(),
            'type' => $type,
            'function' => $func,
            'scope' => end($this->scopeStack), // Escopo atual (topo da pilha)
            'line' => $token->getLine(),
            'position' => $token->getInicio()
        ];
    }

    private function manageScope(string $nonTerminal): void {
        if (in_array(strtoupper($nonTerminal), ['FUNCAO', 'SE', 'SENAO', 'ENQUANTO', 'PARA', 'CONTROLEFLUXO'])) {
            $this->scopeCounter++; // Incrementa o contador para novo escopo
            $this->scopeStack[] = $this->scopeCounter; // Adiciona à pilha de escopos
        }
    }

    private function endScope(): void {
        if (count($this->scopeStack) > 1) { // Evita remover o escopo global
            array_pop($this->scopeStack);
        }
    }
}
