<?php
require_once "./lexical/Token.php";
require_once "./lexical/Lexical.php";
require_once "./lexical/Automato.php";

class AnalisadorSintaticoPreditivo {
    private array $tokens;
    private int $currentTokenIndex = 0;
    private array $errors = [];

    public function __construct(array $tokens = []) {
        $this->tokens = $tokens;
    }

    // Método para validar o programa completo
    public function validarPrograma(string $codigoFonte): array {
        $analisadorLexico = new Lexical($codigoFonte);
        $resultadosLexicos = $analisadorLexico->validate(false)['resp'];

        if (!empty($resultadosLexicos['errors'])) {
            return ["Erro Léxico" => $resultadosLexicos['errors']];
        }

        $this->tokens = $resultadosLexicos['tokens'];
        $this->currentTokenIndex = 0;
        $this->errors = [];

        $this->parseProgram();

        if (!empty($this->errors)) {
            return [false, "Erro Sintático". $this->errors[0]];
        }

        return [true, "Sucesso Código válido!"];
    }

    // Produção principal
    public function parseProgram(): void {
        if ($this->match("PROGRAM")) {
            $this->advanceToken();
            if ($this->match("IDENTIFICADORES")) {
                $this->advanceToken();
                if ($this->match("ABREPAREN")) {
                    $this->advanceToken();
                    if ($this->match("FECHAPAREN")) {
                        $this->advanceToken();
                        if ($this->match("ABRECHAVES")) {
                            $this->advanceToken();
                            $this->parseListaComandos();
                            if (!$this->match("FECHACHAVES")) {
                                $this->addError("Esperado '}' no final do programa", $this->getCurrentToken());
                            }
                        } else {
                            $this->addError("Esperado '{' no início do programa", $this->getCurrentToken());
                        }
                    } else {
                        $this->addError("Esperado ')' após identificadores", $this->getCurrentToken());
                    }
                } else {
                    $this->addError("Esperado '(' após 'PROGRAM'", $this->getCurrentToken());
                }
            } else {
                $this->addError("IDENTIFICADOR esperado após 'PROGRAM'", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado 'PROGRAM' no início do programa", $this->getCurrentToken());
        }
    }

    // Produção: Var ::= Tipo IDENTIFICADORES PONTOVIRGULA
    private function parseVar(): void {
        $this->parseTipo();
        if ($this->match("IDENTIFICADORES")) {
            $this->advanceToken();
            if ($this->match("PONTOVIRGULA")) {
                $this->advanceToken();
            } else {
                $this->addError("Esperado ';' após IDENTIFICADORES", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado IDENTIFICADORES após Tipo", $this->getCurrentToken());
        }
    }

    // Produção: Tipo ::= INT | CHAR | FLOAT | ARRAY
    private function parseTipo(): void {
        if ($this->match("INT") || $this->match("CHAR") || $this->match("FLOAT") || $this->match("ARRAY")) {
            $this->advanceToken();
        } else {
            $this->addError("Tipo inválido", $this->getCurrentToken());
        }
    }

    // Produção: Comando ::= Atribuicao | Leitura | Impressao | Retorno | ChamadaFuncao | If | For | While
    private function parseCommand(): void {
        $token = $this->getCurrentToken();
        switch ($token->getName()) {
            case "IDENTIFICADORES":
                $this->parseAtribuicao();
                break;
            case "READ":
                $this->parseLeitura();
                break;
            case "WRITE":
                $this->parseImpressao();
                break;
            case "RETURN":
                $this->parseRetorno();
                break;
            case "IF":
                $this->parseIf();
                break;
            case "FOR":
                $this->parseFor();
                break;
            case "WHILE":
                $this->parseWhile();
                break;
            default:
                $this->addError("Comando inválido ou inesperado", $token);
        }
    }

    // Produção: Atribuicao ::= IDENTIFICADORES IGUAL Expressao PONTOVIRGULA
    private function parseAtribuicao(): void {
        $this->advanceToken();
        if ($this->match("=")) {
            $this->advanceToken();
            $this->parseExpression();
            if ($this->match("PONTOVIRGULA")) {
                $this->advanceToken();
            } else {
                $this->addError("Esperado ';' após expressão", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado '=' em atribuição", $this->getCurrentToken());
        }
    }

    // Produção: Leitura ::= READ ABREPAREN IDENTIFICADORES FECHAPAREN PONTOVIRGULA
    private function parseLeitura(): void {
        $this->advanceToken();
        if ($this->match("ABREPAREN")) {
            $this->advanceToken();
            if ($this->match("IDENTIFICADORES")) {
                $this->advanceToken();
                if ($this->match("FECHAPAREN")) {
                    $this->advanceToken();
                    if ($this->match("PONTOVIRGULA")) {
                        $this->advanceToken();
                    } else {
                        $this->addError("Esperado ';' após ')'", $this->getCurrentToken());
                    }
                } else {
                    $this->addError("Esperado ')' após IDENTIFICADORES", $this->getCurrentToken());
                }
            } else {
                $this->addError("IDENTIFICADORES esperado após '('", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado '(' após 'READ'", $this->getCurrentToken());
        }
    }


    // Similarmente, você pode expandir para as demais produções, como parseIf(), parseFor(), parseWhile(), parseExpression(), etc.

    // Métodos auxiliares permanecem os mesmos
    private function getCurrentToken(): ?Token {
        return $this->tokens[$this->currentTokenIndex] ?? null;
    }

    private function advanceToken(): void {
        $this->currentTokenIndex++;
    }

    private function match(string $lexeme): bool {
        $token = $this->getCurrentToken();
        return $token && $token->getName() === $lexeme;
    }

    private function addError(string $mensagem, Token $token): void {
        $this->errors[] = "{$mensagem} (Linha: {$token->getLine()}, Coluna: {$token->getInicio()})";
    }

    public function getErrors(): array {
        return $this->errors;
    }
    // Continuação da classe AnalisadorSintaticoPreditivo...

    // Método para analisar o comando IF
    private function parseIf(): void {
        $this->advanceToken(); // Consumir 'IF'
        if ($this->match("ABREPAREN")) {
            $this->advanceToken();
            $this->parseExpression();
            if ($this->match("FECHAPAREN")) {
                $this->advanceToken();
                $this->parseBlock();
            } else {
                $this->addError("Esperado ')' no final da expressão condicional", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado '(' após 'IF'", $this->getCurrentToken());
        }
    }

    // Método para analisar o comando FOR
    private function parseFor(): void {
        $this->advanceToken(); // Consumir 'FOR'
        if ($this->match("ABREPAREN")) {
            $this->advanceToken();
            $this->parseAtribuicao(); // Inicialização
            $this->parseExpression(); // Condição
            if ($this->match("PONTOVIRGULA")) {
                $this->advanceToken();
                $this->parseAtribuicao(); // Incremento
                if ($this->match("FECHAPAREN")) {
                    $this->advanceToken();
                    $this->parseBlock();
                } else {
                    $this->addError("Esperado ')' ao final do 'FOR'", $this->getCurrentToken());
                }
            } else {
                $this->addError("Esperado ';' após a condição no 'FOR'", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado '(' após 'FOR'", $this->getCurrentToken());
        }
    }

    // Método para analisar o comando WHILE
    private function parseWhile(): void {
        $this->advanceToken(); // Consumir 'WHILE'
        if ($this->match("ABREPAREN")) {
            $this->advanceToken();
            $this->parseExpression();
            if ($this->match("FECHAPAREN")) {
                $this->advanceToken();
                $this->parseBlock();
            } else {
                $this->addError("Esperado ')' ao final da expressão condicional no 'WHILE'", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado '(' após 'WHILE'", $this->getCurrentToken());
        }
    }

    // Método para analisar expressões
    private function parseExpression(): void {
        $this->parseTermo(); // Primeira parte da expressão
        while ($this->isOperadorLogico()) {
            $this->advanceToken(); // Consumir o operador lógico
            $this->parseTermo(); // Processar o próximo termo
        }
    }

    // Método auxiliar para verificar operadores lógicos
    private function isOperadorLogico(): bool {
        $operadoresLogicos = ["DUPLAIGUAL", "DIFERENTE", "MENOR_QUE", "MAIOR_QUE", "MENOR_OU_IGUAL", "MAIOR_OU_IGUAL", "AND"];
        return in_array($this->getCurrentToken()->getName(), $operadoresLogicos);
    }

    // Método para analisar termos
    private function parseTermo(): void {
        $token = $this->getCurrentToken();
        if ($token->getName() === "IDENTIFICADORES" || 
            $token->getName() === "CONSTINTEIRAS" || 
            $token->getName() === "CONSTFLUTUANTE") {
            $this->advanceToken(); // Consumir o termo válido
        } else {
            $this->addError("Termo inválido em expressão", $token);
        }
    }

    private function parseImpressao(): void {
        $this->advanceToken(); // Consumir 'WRITE'
        if ($this->match("ABREPAREN")) {
            $this->advanceToken();
            $this->parseExpression(); // Analisar expressão ou identificador
            if ($this->match("FECHAPAREN")) {
                $this->advanceToken();
                if ($this->match("PONTOVIRGULA")) {
                    $this->advanceToken(); // Consumir ';'
                } else {
                    $this->addError("Esperado ';' ao final da impressão", $this->getCurrentToken());
                }
            } else {
                $this->addError("Esperado ')' após a expressão na impressão", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado '(' após 'WRITE'", $this->getCurrentToken());
        }
    }

    // Método para analisar retorno
    private function parseRetorno(): void {
        $this->advanceToken(); // Consumir 'RETURN'
        $this->parseExpression();
        if ($this->match("PONTOVIRGULA")) {
            $this->advanceToken();
        } else {
            $this->addError("Esperado ';' ao final do retorno", $this->getCurrentToken());
        }
    }
    private function parseBlock(): void {
        if ($this->match("ABRECHAVES")) {
            $this->advanceToken(); // Consumir '{'
            
            // Processar uma lista de comandos enquanto o próximo token não for '}'
            while (!$this->match("FECHACHAVES")) {
                $this->parseComando(); // Chama a função que interpreta um comando individual

                // Caso o fim da entrada seja alcançado antes do '}', adicionar erro e sair do loop
                if ($this->isEndOfTokens()) {
                    $this->addError("Esperado '}' ao final do bloco", $this->getCurrentToken());
                    break;
                }
            }

            if ($this->match("FECHACHAVES")) {
                $this->advanceToken(); // Consumir '}'
            } else {
                $this->addError("Esperado '}' para fechar o bloco de comandos", $this->getCurrentToken());
            }
        } else {
            $this->addError("Esperado '{' para iniciar o bloco de comandos", $this->getCurrentToken());
        }
    }

    // Método para analisar um comando dentro de um bloco
    private function parseListaComandos(): void {
        $i=0;
        while (!$this->match("FECHACHAVES")) {
            $this->parseCommand();
            if ($this->isEndOfTokens()) {
                $this->addError("Esperado '}' ao final do bloco", $this->getCurrentToken());
                break; // Sai do loop se o final da entrada for alcançado
            }
        }
    }
    
    // Método para analisar um comando dentro de um bloco
    private function parseComando(): void {
        // Identificar o tipo de comando e chamar a produção correspondente
        $token = $this->getCurrentToken();
    
        switch ($token->getName()) {
            case "IF":
                $this->parseIf();
                break;
            case "FOR":
                $this->parseFor();
                break;
            case "WHILE":
                $this->parseWhile();
                break;
            case "READ":
                $this->parseLeitura();
                break;
            case "WRITE":
                $this->parseImpressao();
                break;
            case "RETURN":
                $this->parseRetorno();
                break;
            default:
                if ($token->getName() === "IDENTIFICADORES") {
                    $this->parseAtribuicao();
                } else {
                    $this->addError("Comando inválido ou inesperado", $token);
                    $this->advanceToken(); // Avança para evitar loop infinito em caso de erro
                }
        }
    
        // Após processar o comando, verifique novamente se estamos no fim dos tokens
        if ($this->isEndOfTokens()) {
            $this->addError("Esperado um comando válido", $this->getCurrentToken());
        }
    }
    private function isEndOfTokens(): bool {
        return $this->currentTokenIndex >= count($this->tokens);
    }
}
