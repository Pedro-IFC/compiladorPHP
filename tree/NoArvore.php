<?php 

class NoArvore {
    public string $nome;
    public array $filhos;
    public function __construct(string $nome) {
        $this->nome = $nome;
        $this->filhos = [];
    }
    public function adicionarFilho(NoArvore $filho) {
        $this->filhos[] = $filho;
    }
    public function imprimir($nivel = 0) {
        echo str_repeat("--", $nivel) . $this->nome . "<br>";
        foreach ($this->filhos as $filho) {
            $filho->imprimir($nivel + 1);
        }
    }
    public function toString($nivel = 0):string{
        $str =str_repeat("--", $nivel) . $this->nome . "\n";
        foreach ($this->filhos as $filho) {
            $str.=$filho->toString($nivel + 1,);
        }
        return $str;
    }
}
