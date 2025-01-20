<?php

class SLRParser {
    private array $gotoTable;
    private array $actionTable;
    private $productions = [
        [42, 6],
        [42, 0],
        [43, 3],
        [43, 0],
        [44, 4],
        [44, 0],
        [45, 2],
        [45, 0],
        [46, 2],
        [46, 0],
        [47, 1],
        [47, 1],
        [47, 1],
        [47, 1],
        [47, 1],
        [48, 3],
        [49, 4],
        [50, 5],
        [51, 11],
        [51, 7],
        [51, 13],
        [51, 7],
        [62, 5],
        [63, 1],
        [63, 1],
        [63, 3],
        [52, 2],
        [52, 1],
        [53, 3],
        [53, 3],
        [53, 0],
        [60, 2],
        [61, 3],
        [61, 3],
        [61, 3],
        [61, 3],
        [61, 3],
        [61, 3],
        [61, 3],
        [61, 0],
        [54, 2],
        [55, 3],
        [55, 3],
        [55, 3],
        [55, 0],
        [56, 1],
        [56, 1],
        [56, 1],
        [56, 3],
        [57, 1],
        [57, 1],
        [57, 1],
        [58, 2],
        [58, 0],
        [59, 3],
        [59, 0],
        [64, 1],
        [64, 1],
        [64, 1],
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
        $stack = [0]; // Stack starts with state 0
        $index = 0; // Token pointer

        while (true) {
            $state = end($stack);
            $currentToken = $tokens[$index] ?? null;
            $tokenName = $currentToken ? $currentToken->getName() : '$'; // Use '$' for end of input

            echo "<hr>";
            echo "Estado: ";
            var_dump($state);
            echo "<br>";
            echo "Token: ";
            var_dump($tokenName);
            $action = $this->actionTable[$state][$tokenName] ?? null;
            echo "<br>";
            var_dump($this->actionTable[$state]);
            if ($action === null) {
                $this->syntaxError($currentToken);
                return false;
            }

            echo "<br>";
            echo "Acao: ";
            var_dump($action);
            echo "<br>";
            echo "Pilha: ";
            var_dump($stack);
            echo "<br>";
            echo "<br>";

            if ($action['type'] === 'SHIFT') {
                $stack[] = $action['state'];
                $index++;
            } elseif ($action['type'] === 'REDUCE') {
                var_dump($action['rule']);
                echo "<br>";

                $ruleCount = $action['rule'];
                $removedCount = 0;

                foreach ($stack as $key => $value) {
                    if ($removedCount > $ruleCount) {
                        unset($stack[$key]); // Remove os elementos excedentes
                    } else {
                        $removedCount++;
                    }
                }

                $state = end($stack);
                if(isset($this->gotoTable[$state])){
                    $lhs = array_keys($this->gotoTable[$state])[0]; // Assume one LHS per reduce
                    $stack[] = $this->gotoTable[$state][$lhs];
                }else{
                    $index++;
                }
            } elseif ($action['type'] === 'ACCEPT') {
                return true;
            }
        }
    }

    private function syntaxError(?Token $token): void {
        if ($token) {
            echo "Syntax error at line {$token->getLine()}, near '{$token->getLexeme()}'.\n";
        } else {
            echo "Syntax error at end of input.\n";
        }
    }
}