<?php

class AnalisadorLRSimples {
    private array $grammar;
    private array $parsingTable;

    public function __construct() {
        $this->grammar = SLRDoc::$grammar;
        $this->parsingTable = SLRDoc::$parsingTable;
    }

    public function parse(array $tokens): bool {
        $stack = [0];
        $tokenIndex = 0;

        while (true) {
            $currentState = end($stack);
            $currentToken = $tokens[$tokenIndex] ?? null;
            echo "<hr>";
            var_dump($currentState);
            echo "/";
            var_dump($currentToken);
            
            $action = $this->getAction($currentState, $currentToken);

            if (!$action) {
                throw new Exception("Erro de análise na linha {$currentToken->getLine()} em '{$currentToken->getLexeme()}'.");
            }

            if ($action["type"] === "shift") {
                $stack[] = $action["state"];
                $tokenIndex++;
            } elseif ($action["type"] === "reduce") {
                $rule = $this->grammar[$action["rule"]];
                $head = $rule["head"];
                $bodyLength = count($rule["body"]);

                for ($i = 0; $i < $bodyLength * 2; $i++) {
                    array_pop($stack);
                }

                $currentState = end($stack);
                $stack[] = $head;
                $stack[] = $this->getGoto($currentState, $head);
            } elseif ($action["type"] === "accept") {
                return true;
            }
        }

        return false;
    }

    private function getAction(int $state, ?Token $token): ?array {
        $symbol = $token ? $token->getName() : "$";
        return $this->parsingTable[$state]["action"][$symbol] ?? null;
    }

    private function getGoto(int $state, string $symbol): ?int {
        return $this->parsingTable[$state]["goto"][$symbol] ?? null;
    }
}