<?php 
class Token{
    private string $name;
    private string $lexeme;
    private int $inicio;
    private int $line;
    function __construct(string $name, string $lexeme, int $inicio, int $line) {
        $this->name = $name;
        $this->lexeme = $lexeme;
        $this->inicio = $inicio;
        $this->line = $line;
    }

    public function getName(bool $lower = false) : string{
        if($lower){
            return strtolower($this->name);
        }
        return $this->name;
    }
    public function setName(string $name){
        $this->name = $name;
    }
    public function getLexeme() : string{
        return $this->lexeme;
    }
    public function setLexeme(string $lexeme){
        $this->lexeme = $lexeme;
    }
    public function getInicio() : int{
        return $this->inicio;
    }
    public function setInicio(int $inicio){
        $this->inicio = $inicio;
    }
    public function setLine(int $line){
        $this->line = $line;
    }
    public function getLine() : int{
        return $this->line;
    }
    public function __toString() {
       return $this->getName()."-".$this->getLexeme();
    }
}