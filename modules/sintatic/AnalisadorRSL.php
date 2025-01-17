<?php

class SLRParser {
    private array $gotoTable;
    private array $actionTable;

    public function __construct(string $jsonFilePath) {
        $this->loadParsingTable($jsonFilePath);
    }

    private function loadParsingTable(string $filePath): void {
        $data = json_decode(file_get_contents($filePath), true);
        if ($data === null) {
            throw new Exception("Invalid JSON file");
        }

        $this->gotoTable = [];
        $this->actionTable = [];

        foreach ($data as $state => $transitions) {
            foreach ($transitions as $symbol => $action) {
                if (str_starts_with($action, 'SHIFT')) {
                    $nextState = (int) filter_var($action, FILTER_SANITIZE_NUMBER_INT);
                    $this->actionTable[$state][$symbol] = ['type' => 'SHIFT', 'state' => $nextState, 'raw' => $action];
                } elseif (str_starts_with($action, 'REDUCE')) {
                    $ruleIndex = (int) filter_var($action, FILTER_SANITIZE_NUMBER_INT);
                    $this->actionTable[$state][$symbol] = ['type' => 'REDUCE', 'rule' => $ruleIndex, 'raw' => $action];
                } elseif ($action === 'ACCEPT') {
                    $this->actionTable[$state][$symbol] = ['type' => 'ACCEPT', 'raw' => $action];
                } else {
                    $this->gotoTable[$state][$symbol] = (int) $action;
                }
            }
        }
    }

    public function parse(array $tokens): bool {
        $stack = [0]; // Stack starts with state 0
        $index = 0; // Token pointer

        while (true) {
            $state = end($stack);
            $currentToken = $tokens[$index] ?? null;
            $tokenName = $currentToken ? $currentToken->getName() : '$'; // Use '$' for end of input

            $action = $this->actionTable[$state][$tokenName] ?? null;

            if ($action === null) {
                $this->syntaxError($currentToken);
                return false;
            }

            echo "<hr>";
            echo "Estado: ";
            var_dump($state);
            echo "<br>";
            echo "Token: ";
            var_dump($tokenName);
            echo "<br>";
            echo "Acao: ";
            var_dump($action['raw']);
            echo "<br>";
            echo "Pilha: ";
            var_dump($stack);

            if ($action['type'] === 'SHIFT') {
                $stack[] = $action['state'];
                $index++;
            } elseif ($action['type'] === 'REDUCE') {
                var_dump($action['rule']);

                $ruleCount = $action['rule'];
                $removedCount = 0;

                // Verificar se o valor já existe na pilha antes de removê-lo
                $foundInStack = false;
                foreach ($stack as $key => $value) {
                    if ($value == $ruleCount) {
                        $foundInStack = true;
                        break;
                    }
                }

                if (!$foundInStack) {
                    // Se não encontrado, inseri na pilha, mas não remove
                    $stack[] = $ruleCount;
                } else {
                    // Caso contrário, continue removendo os elementos conforme a lógica original
                    foreach ($stack as $key => $value) {
                        if ($removedCount >= $ruleCount) {
                            unset($stack[$key]); // Remove os elementos excedentes
                        } else {
                            $removedCount++;
                        }
                    }
                }

                $state = end($stack);
                if(isset($this->gotoTable[$state])){
                    $lhs = array_keys($this->gotoTable[$state])[0]; // Assume one LHS per reduce
                    $stack[] = $this->gotoTable[$state][$lhs];
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