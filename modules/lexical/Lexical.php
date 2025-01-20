<?php
require_once "Token.php";
require_once "AnalisadorLexico.php";

class Lexical {
    private string $string = "";
    protected string $json = './data/lexical/tabelalexica.json';
    private array $identificadores = [];
    private array $constantes = [];
    private array $palavrasReservadas = [];
    private array $operadores = [];
    private array $tokenList = [];
    public function __construct(string $string){
        $this->setString($string);
        if(!file_exists($this->json)){
            echo "Erro sem arquivo de transicoes";
            die();
        }
    }
    public function setString(string $string) : void{
        $this->string = $string;
    }
    public function setIdentificador(string $lexeme, int $inicio, int $fim, $line) : void{
        $this->identificadores[] = new Token("Identificador", $lexeme, $inicio, $fim, $line);
    }
    public function setConstante(string $lexeme, int $inicio, int $fim, $line) : void{
        $this->constantes[] = new Token("Constante", $lexeme, $inicio, $fim, $line);
    }
    public function setPalavraReservada(string $lexeme, int $inicio, int $fim, $line) : void{
        $this->palavrasReservadas[] = new Token("Palavra Reservada", $lexeme, $inicio, $fim, $line);
    }
    public function setOperador(string $lexeme, int $inicio, int $fim, $line) : void{
        $this->operadores[] = new Token("Operador", $lexeme, $inicio, $fim, $line);
    }
    public function getTokenList(){
        return $this->tokenList;
    }
    public function validate(bool $serialize) : array{
        $Lexical = json_decode(file_get_contents($this->json), true);
        $response = AnalisadorLexico::parseTokens($this->string, $Lexical, $serialize);
        $this->tokenList = AnalisadorLexico::$tokens;
        return $response;
    }
} 