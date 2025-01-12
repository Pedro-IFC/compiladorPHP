<?php

class AnalisadorSintaticoDescidaRecursiva {
    private array $tokens;
    private int $posicaoAtual = 0;

    public function __construct(array $tokens) {
        $this->tokens = $tokens;
    }

    // Método que consome um token se ele corresponder ao nome esperado
    private function consumir(string $nomeEsperado) {
        if(isset($this->tokens[$this->posicaoAtual])){
            if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === $nomeEsperado) {
                $this->posicaoAtual++; // Consome o token
                return true;
            } else {
                echo ("Erro de sintaxe: Esperado '{$nomeEsperado}', encontrado '{$this->tokens[$this->posicaoAtual]->getName()}'");
                return false;
            }
        }else{
            echo ("Erro de sintaxe: Esperado 'fim'");
            return false;
        }
    }

    // Programa principal (começo do código)
    public function programa() {
        try {
            $this->consumir("INICIO"); // Espera o token "inicio"
            $this->lista_funcoes(); // Chama a função de validação das funções
            return $this->consumir("FIM");
        } catch (Exception $e) {
            return false;
        }
    }

    // Lista de funções (pode ser vazia)
    public function lista_funcoes() {
        if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "FUNCTION") {
            $this->funcao(); // Consome e processa a função
            $this->lista_funcoes(); // Recursão DO processar mais funções
        }
    }

    // Definição de uma função
    public function funcao() {
        $this->consumir('FUNCTION');
        $this->tipo(); // consumindo o tipo
        $this->consumir('IDENTIFICADORES'); // consumindo o identificador
    
        $this->consumir('ABREPAREN'); // consumindo '('
        $this->DOmetros(); // analisa parâmetros
        $this->consumir('FECHAPAREN'); // consumindo ')'
        $this->consumir('ABRECHAVES'); // consumindo '{'
        
        $this->lista_comandos();
        
        $this->consumir('FECHACHAVES');  // Garantindo que a função seja fechada
    }
    

    // Definição dos parâmetros de uma função
    public function DOmetros() {
        if ($this->posicaoAtual < count($this->tokens) && in_array($this->tokens[$this->posicaoAtual]->getName(), ["CHAR", "INT", "FLOAT", "ARRAY"])) {
            $this->tipo(); // Tipo do parâmetro
            $this->identificador(); // Nome do parâmetro
            $this->DOmetros_lista(); // Parâmetros adicionais
        }
    }

    // Parâmetros adicionais (se houver)
    public function DOmetros_lista() {
        if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "VIRGULA") {
            $this->consumir("VIRGULA"); // Espera ","
            $this->tipo(); // Tipo do próximo parâmetro
            $this->identificador(); // Nome do próximo parâmetro
            $this->DOmetros_lista(); // Chama recursivamente DO parâmetros adicionais
        }
    }

    // Tipo de dado (char, int, float, array)
    public function tipo() {
        if ($this->posicaoAtual < count($this->tokens) && in_array($this->tokens[$this->posicaoAtual]->getName(), ["CHAR", "INT", "FLOAT", "ARRAY"])) {
            $this->consumir($this->tokens[$this->posicaoAtual]->getName()); // Consome o tipo
        } else {
            throw new Exception("Erro de sintaxe: Esperado tipo (char, int, float, array), encontrado '{$this->tokens[$this->posicaoAtual]->getName()}'");
        }
    }

    // Identificador (nome de variável ou função)
    public function identificador() {
        if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "IDENTIFICADORES") {
            $this->consumir("IDENTIFICADORES"); // Consome o identificador
        } else {
            throw new Exception("Erro de sintaxe: Esperado identificador, encontrado '{$this->tokens[$this->posicaoAtual]->getName()}'");
        }
    }

    // Lista de comandos de uma função
    public function lista_comandos() {
        if ($this->posicaoAtual < count($this->tokens) && in_array($this->tokens[$this->posicaoAtual]->getName(), ["VAR", "READ", "WRITE", "RETORNE", "IDENTIFICADORES", "IF", "DO", "WHILE"])) {
            $this->comando(); // Processa o comando
            $this->lista_comandos(); // Chama recursivamente DO mais comandos
        }
    }

    // Comando de função
    public function comando() {
        if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "VAR") {
            $this->declaracao_variavel(); // Declaração de variável
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "IDENTIFICADORES") {
            $this->atribuicao(); // Atribuição de valor
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "READ") {
            $this->leitura(); // Leitura de variável
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "WRITE") {
            $this->impressao(); // Impressão de valor
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "RETORNE") {
            $this->retorno(); // Retorno de valor
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "IDENTIFICADORES") {
            $this->chamada_funcao(); // Chamada de função
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "IF") {
            $this->selecao(); // Comando de seleção (if)
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "DO" || $this->tokens[$this->posicaoAtual]->getName() === "WHILE") {
            $this->repeticao(); // Comando de repetição (for, while)
        }
    }

    // Outros métodos de análise (como declaracao_variavel, atribuicao, leitura, impressao, etc.)
    
    // Exemplo de um comando de declaração de variável
    public function declaracao_variavel() {
        $this->tipo();
        $this->identificador();
        $this->consumir("PONTOVIRGULA"); // Espera ";"
    }

    // Método de atribuição (exemplo)
    public function atribuicao() {
        $this->identificador();
        $this->consumir("IGUAL");
        $this->expressao();
        $this->consumir("PONTOVIRGULA");
    }

    // Métodos de leitura, impressão, retorno, etc., são semelhantes a esses exemplos.
    
    // Método de expressões, por exemplo, de uma operação aritmética
    public function expressao() {
        $this->termo();
        $this->expressao_opcional();
    }

    // Método de termos na expressão (como fatores)
    public function termo() {
        $this->fator();
        $this->termo_opcional();
    }
    // Método de leitura (READ)
    public function leitura() {
        $this->consumir("READ");
        $this->consumir("ABREPAREN");
        $this->identificador(); // Espera identificador
        $this->consumir("FECHAPAREN");
        $this->consumir("PONTOVIRGULA"); // Espera ";"
    }

    // Método de impressão (imprima)
    public function impressao() {
        $this->consumir("WRITE");
        $this->consumir("ABREPAREN");
        $this->expressao(); // Espera uma expressão
        $this->consumir("FECHAPAREN");
        $this->consumir("PONTOVIRGULA"); // Espera ";"
    }

    // Método de retorno (retorne)
    public function retorno() {
        $this->consumir("RETORNE");
        $this->expressao(); // Espera uma expressão
        $this->consumir("PONTOVIRGULA"); // Espera ";"
    }

    // Método de chamada de função
    public function chamada_funcao() {
        $this->identificador(); // Nome da função
        $this->consumir("ABREPAREN");
        $this->argumentos(); // Argumentos passados DO a função
        $this->consumir("FECHAPAREN");
        $this->consumir("PONTOVIRGULA"); // Espera ";"
    }

    // Argumentos da chamada de função (expressões)
    public function argumentos() {
        if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() !== "FECHAPAREN") {
            $this->expressao(); // Espera uma expressão
            $this->argumentos_lista(); // Verifica argumentos adicionais
        }
    }

    // Lista de argumentos (mais expressões seDOdas por vírgula)
    public function argumentos_lista() {
        if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "VIRGULA") {
            $this->consumir("VIRGULA");
            $this->expressao(); // Espera mais uma expressão
            $this->argumentos_lista(); // Chama recursivamente DO mais argumentos
        }
    }

    // Comando de seleção (se-else)
    public function selecao() {
        $this->consumir("IF");
        $this->consumir("ABREPAREN");
        $this->condicao(); // Expressão condicional
        $this->consumir("FECHAPAREN");
        $this->consumir("ABRECHAVES");
        $this->lista_comandos(); // Comandos executados se a condição for verdadeira
        $this->consumir("FECHACHAVES");
        $this->selecao_senao(); // Parte do "senao" (se houver)
    }

    // Parte do "senao" da seleção
    public function selecao_senao() {
        if ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "SENAO") {
            $this->consumir("SENAO");
            $this->consumir("ABRECHAVES");
            $this->lista_comandos(); // Comandos executados se a condição for falsa
            $this->consumir("FECHACHAVES");
        }
    }

    // Comando de repetição (DO/WHILE)
    public function repeticao() {
        if ($this->tokens[$this->posicaoAtual]->getName() === "DO") {
            $this->consumir("DO");
            $this->consumir("ABREPAREN");
            $this->atribuicao(); // Atribuição da variável de controle
            $this->condicao(); // Condição de repetição
            $this->consumir("PONTOVIRGULA");
            $this->atribuicao(); // Atualização da variável de controle
            $this->consumir("FECHAPAREN");
            $this->consumir("ABRECHAVES");
            $this->lista_comandos(); // Comandos a serem repetidos
            $this->consumir("FECHACHAVES");
        } elseif ($this->tokens[$this->posicaoAtual]->getName() === "WHILE") {
            $this->consumir("WHILE");
            $this->consumir("ABREPAREN");
            $this->condicao(); // Condição de repetição
            $this->consumir("FECHAPAREN");
            $this->consumir("ABRECHAVES");
            $this->lista_comandos(); // Comandos a serem repetidos
            $this->consumir("FECHACHAVES");
        }
    }

    // Condição de repetição ou seleção (expressão condicional)
    public function condicao() {
        $this->expressao(); // Condição
        $this->operador_relacional(); // Operador relacional
        $this->expressao(); // Segunda expressão
    }

    // Operadores relacionais (==, !=, <, >, <=, >=)
    public function operador_relacional() {
        if ($this->posicaoAtual < count($this->tokens) && in_array($this->tokens[$this->posicaoAtual]->getName(), ["DUPLAIGUAL", "DIFERENTE", "MENOR_QUE", "MAIOR_QUE", "MENOR_OU_IGUAL", "MAIOR_OU_IGUAL"])) {
            $this->consumir($this->tokens[$this->posicaoAtual]->getName()); // Consome o operador
        } else {
            throw new Exception("Erro de sintaxe: Esperado operador relacional, encontrado '{$this->tokens[$this->posicaoAtual]->getName()}'");
        }
    }

    // Expressão adicional (operadores aritméticos)
    public function expressao_opcional() {
        if ($this->posicaoAtual < count($this->tokens) && in_array($this->tokens[$this->posicaoAtual]->getName(), ["MAIS", "MENOS", "ASTERISTICO", "DIVISAO"])) {
            $this->operador_aritmetico(); // Operador aritmético
            $this->termo(); // Proximo termo da expressão
            $this->expressao_opcional(); // Recursão DO expressão adicional
        }
    }

    // Operadores aritméticos (+, -, *, /)
    public function operador_aritmetico() {
        if ($this->posicaoAtual < count($this->tokens) && in_array($this->tokens[$this->posicaoAtual]->getName(), ["MAIS", "MENOS", "ASTERISTICO", "DIVISAO"])) {
            $this->consumir($this->tokens[$this->posicaoAtual]->getName());
        } else {
            throw new Exception("Erro de sintaxe: Esperado operador aritmético, encontrado '{$this->tokens[$this->posicaoAtual]->getName()}'");
        }
    }

    // Termo adicional (operadores aritméticos em termos)
    public function termo_opcional() {
        if ($this->posicaoAtual < count($this->tokens) && in_array($this->tokens[$this->posicaoAtual]->getName(), ["ASTERISTICO", "DIVISAO", "MODULODADIVISAO"])) {
            $this->operador_aritmetico(); // Operador multiplicativo
            $this->fator(); // Próximo fator
            $this->termo_opcional(); // Recursão
        }
    }

    // Fator da expressão (identificadores, constantes ou parênteses)
    public function fator() {
        if ($this->posicaoAtual < count($this->tokens) && ($this->tokens[$this->posicaoAtual]->getName() === "IDENTIFICADORES" || $this->tokens[$this->posicaoAtual]->getName() === "CONSTINTEIRAS" || $this->tokens[$this->posicaoAtual]->getName() === "CONSTFLUTUANTE")) {
            $this->consumir($this->tokens[$this->posicaoAtual]->getName()); // Consome o identificador ou constante
        } elseif ($this->posicaoAtual < count($this->tokens) && $this->tokens[$this->posicaoAtual]->getName() === "ABREPAREN") {
            $this->consumir("ABREPAREN");
            $this->expressao(); // Expressão entre parênteses
            $this->consumir("FECHAPAREN");
        } else {
            throw new Exception("Erro de sintaxe: Esperado fator (identificador, constante ou expressão), encontrado '{$this->tokens[$this->posicaoAtual]->getName()}'");
        }
    }
}