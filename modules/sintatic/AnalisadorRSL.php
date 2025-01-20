<?php

class SLRParser {
    private array $gotoTable;
    private array $actionTable;
    private $productions = [
        [42, 6], [42, 0], [43, 3], [43, 0], [44, 4], [44, 0], [45, 2], [45, 0],
        [46, 2], [46, 0], [47, 1], [47, 1], [47, 1], [47, 1], [47, 1], [48, 3],
        [49, 4], [50, 5], [51, 11], [51, 7], [51, 13], [51, 7], [62, 5], [63, 1],
        [63, 1], [63, 3], [52, 2], [52, 1], [53, 3], [53, 3], [53, 0], [60, 2],
        [61, 3], [61, 3], [61, 3], [61, 3], [61, 3], [61, 3], [61, 3], [61, 0],
        [54, 2], [55, 3], [55, 3], [55, 3], [55, 0], [56, 1], [56, 1], [56, 1],
        [56, 3], [57, 1], [57, 1], [57, 1], [58, 2], [58, 0], [59, 3], [59, 0],
        [64, 1], [64, 1], [64, 1],
    ];

    public function __construct(string $jsonFilePath) {
        $this->loadParsingTable($jsonFilePath);
    }

    private function loadParsingTable(string $filePath): void {
        $data = json_decode(file_get_contents($filePath), true);
        if ($data === null) {
            throw new Exception("Invalid JSON file");
        }

        $this->gotoTable = $data['goto'];
        $this->actionTable = $data['actionTable'];
    }

    public function parse(array $tokens): bool {
        $stack = [0];
        $inputPointer = 0;
    
        while (true) {
            $state = end($stack);
            $token = $tokens[$inputPointer] ?? new Token('$', '$', 0, 0);
            $tokenName = $token->getName();
    
            echo "Estado atual: $state, Token: $tokenName<br>";
    
            if (!isset($this->actionTable[$state][$tokenName])) {
                throw new Exception("Erro de sintaxe na linha " . $token->getLine() . ", token inesperado: " . $tokenName);
            }
    
            $action = $this->actionTable[$state][$tokenName];
    
            if ($action['type'] === 'SHIFT') {
                echo "SHIFT para estado: " . $action['state'] . "<br>";
                $stack[] = $action['state'];
                $inputPointer++;
            } elseif ($action['type'] === 'REDUCE') {
                $rule = $this->productions[$action['rule']];
                echo "REDUCE usando regra: " . $action['rule'] . "<br>";
    
                for ($i = 0; $i < $rule[1]; $i++) {
                    array_pop($stack);
                }
                $topState = end($stack);
                $gotoState = $this->gotoTable[$topState][$rule[0]] ?? null;
    
                if ($gotoState === null) {
                    throw new Exception("Erro ao aplicar redução na linha " . $token->getLine());
                }
    
                echo "GOTO para estado: $gotoState<br>";
                $stack[] = $gotoState;
            } elseif ($action['type'] === 'ACCEPT') {
                echo "Aceitação bem-sucedida!<br>";
                return true;
            }
        }
    }
    
}
