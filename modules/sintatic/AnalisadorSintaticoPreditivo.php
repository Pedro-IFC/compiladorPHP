<?php

class AnalisadorSintaticoPreditivo {
    private array $tokens;
    private int $currentTokenIndex;
    private Token $currentToken;

    public function __construct(array $tokens) {
        $this->tokens = $tokens;
        $this->currentTokenIndex = 0;
        $this->currentToken = $this->tokens[$this->currentTokenIndex];
    }

    private function match(string $expectedTokenName): void {
        if ($this->currentToken->getName() === $expectedTokenName) {
            $this->advance();
        } else {
            throw new Exception("Erro de sintaxe. Esperava '{$expectedTokenName}', mas encontrou '{$this->currentToken->getLexeme()}' na linha {$this->currentToken->getLine()}");
        }
    }

    private function advance(): void {
        $this->currentTokenIndex++;
        if ($this->currentTokenIndex < count($this->tokens)) {
            $this->currentToken = $this->tokens[$this->currentTokenIndex];
        }
    }

    public function programa(): void {
        $this->match("INICIO");
        $this->lista_funcoes();
        $this->match("FIM");
    }

    private function lista_funcoes(): void {
        if ($this->currentToken->getName() === "FIM") {
            return; // ε (epsilon) case
        }
        $this->funcao();
        $this->lista_funcoes();
    }

    private function funcao(): void {
        $this->match("FUNCTION");
        $this->tipo();
        $this->match("IDENTIFICADORES");
        $this->match("ABREPAREN");
        $this->parametros();
        $this->match("FECHAPAREN");
        $this->match("ABRECHAVES");
        $this->lista_comandos();
        $this->match("FECHACHAVES");
    }

    private function parametros(): void {
        if ($this->currentToken->getName() === "FECHAPAREN") {
            return; // ε (epsilon) case
        }
        $this->tipo();
        $this->match("IDENTIFICADORES");
        $this->parametros_lista();
    }

    private function parametros_lista(): void {
        if ($this->currentToken->getName() === "FECHAPAREN") {
            return; // ε (epsilon) case
        }
        $this->match("VIRGULA");
        $this->tipo();
        $this->match("IDENTIFICADORES");
        $this->parametros_lista();
    }

    private function tipo(): void {
        $tokenName = $this->currentToken->getName();
    
        if (in_array($tokenName, ["CHAR", "INT", "FLOAT", "ARRAY"])) {
            $this->advance(); // Aceita o token e avança para o próximo
        } else {
            throw new Exception("Erro de sintaxe: tipo esperado ('char', 'int', 'float', 'array') na linha {$this->currentToken->getLine()}.");
        }
    }

    private function lista_comandos(): void {
        if ($this->currentToken->getName() === "FECHACHAVES") {
            return; // ε (epsilon) case
        }
        $this->comando();
        $this->lista_comandos();
    }

    private function comando(): void {
        $tokenName = $this->currentToken->getName();
    
        switch ($tokenName) {
            case "CHAR":
            case "INT":
            case "FLOAT":
            case "ARRAY":
                $this->declaracao_variavel();
                break;
    
            case "IDENTIFICADORES":
                $this->atribuicao();
                break;
    
            case "READ":
                $this->leitura();
                break;
    
            case "WRITE":
                $this->impressao();
                break;
    
            case "RETURN":
                $this->retorno();
                break;
    
            case "IF":
                $this->selecao();
                break;
    
            case "WHILE":
            case "DO":
                $this->repeticao();
                break;
    
            default:
                throw new Exception("Erro de sintaxe: comando inesperado '{$this->currentToken->getLexeme()}' na linha {$this->currentToken->getLine()}.");
        }
    }
    
    private function declaracao_variavel(): void {
        $this->tipo();
        $this->match("IDENTIFICADORES");
        $this->match("PONTOVIRGULA");
    }
    
    private function atribuicao(): void {
        $this->match("IDENTIFICADORES");
        $this->match("IGUAL");
        $this->expressao();
        $this->match("PONTOVIRGULA");
    }
    
    private function leitura(): void {
        $this->match("READ");
        $this->match("ABREPAREN");
        $this->match("IDENTIFICADORES");
        $this->match("FECHAPAREN");
        $this->match("PONTOVIRGULA");
    }
    
    private function impressao(): void {
        $this->match("WRITE");
        $this->match("ABREPAREN");
        $this->expressao();
        $this->match("FECHAPAREN");
        $this->match("PONTOVIRGULA");
    }
    
    private function retorno(): void {
        $this->match("RETURN");
        $this->expressao();
        $this->match("PONTOVIRGULA");
    }
    
    private function selecao(): void {
        $this->match("IF");
        $this->match("ABREPAREN");
        $this->condicao();
        $this->match("FECHAPAREN");
        $this->match("ABRECHAVES");
        $this->lista_comandos();
        $this->match("FECHACHAVES");
        $this->selecao_senao();
    }
    
    private function selecao_senao(): void {
        if ($this->currentToken->getName() === "ELIF") {
            $this->match("ELIF");
            $this->match("ABRECHAVES");
            $this->lista_comandos();
            $this->match("FECHACHAVES");
        }
    }
    
    private function repeticao(): void {
        if ($this->currentToken->getName() === "WHILE") {
            $this->match("WHILE");
            $this->match("ABREPAREN");
            $this->condicao();
            $this->match("FECHAPAREN");
            $this->match("ABRECHAVES");
            $this->lista_comandos();
            $this->match("FECHACHAVES");
        } elseif ($this->currentToken->getName() === "DO") {
            $this->match("DO");
            $this->match("ABRECHAVES");
            $this->lista_comandos();
            $this->match("FECHACHAVES");
            $this->match("WHILE");
            $this->match("ABREPAREN");
            $this->condicao();
            $this->match("FECHAPAREN");
            $this->match("PONTOVIRGULA");
        } else {
            throw new Exception("Erro de sintaxe: comando de repetição esperado na linha {$this->currentToken->getLine()}.");
        }
    }
    
    private function condicao(): void {
        $this->expressao();
        $this->operador_relacional();
        $this->expressao();
    }
    
    private function operador_relacional(): void {
        $tokenName = $this->currentToken->getName();
        if (in_array($tokenName, ["DUPLAIGUAL", "DIFERENTE", "MENOR_QUE", "MAIOR_QUE", "MENOR_OU_IGUAL", "MAIOR_OU_IGUAL"])) {
            $this->advance();
        } else {
            throw new Exception("Erro de sintaxe: operador relacional esperado na linha {$this->currentToken->getLine()}.");
        }
    }
    
    private function expressao(): void {
        $this->termo();
        $this->expressao_opcional();
    }
    
    private function expressao_opcional(): void {
        if ($this->isOperadorAritmetico($this->currentToken->getName())) {
            $this->operador_aritmetico();
            $this->termo();
            $this->expressao_opcional();
        }
    }
    
    private function termo(): void {
        $this->fator();
        $this->termo_opcional();
    }
    
    private function termo_opcional(): void {
        if ($this->isOperadorAritmetico($this->currentToken->getName())) {
            $this->operador_aritmetico();
            $this->fator();
            $this->termo_opcional();
        }
    }
    
    private function fator(): void {
        $tokenName = $this->currentToken->getName();
        if ($tokenName === "ABREPAREN") {
            $this->match("ABREPAREN");
            $this->expressao();
            $this->match("FECHAPAREN");
        } elseif ($tokenName === "IDENTIFICADORES") {
            $this->match("IDENTIFICADORES");
        } elseif (in_array($tokenName, ["CONSTINTEIRAS", "CONSTFLUTUANTE"])) {
            $this->advance();
        } else {
            throw new Exception("Erro de sintaxe: fator esperado na linha {$this->currentToken->getLine()}.");
        }
    }
    
    private function operador_aritmetico(): void {
        $tokenName = $this->currentToken->getName();
        if (in_array($tokenName, ["MAIS", "MENOS", "ASTERISTICO", "DIVISAO"])) {
            $this->advance();
        } else {
            throw new Exception("Erro de sintaxe: operador aritmético esperado na linha {$this->currentToken->getLine()}.");
        }
    }
    
    private function isOperadorAritmetico(string $tokenName): bool {
        return in_array($tokenName, ["MAIS", "MENOS", "ASTERISTICO", "DIVISAO"]);
    }
    

    public function parse(): bool {
        try {
            $this->programa();
            return true;
        } catch (Exception $e) {
            echo "<h3 style='color: red'>Erro de análise sintática: " . $e->getMessage() . "</h3>\n";
            return false;
        }
    }
}