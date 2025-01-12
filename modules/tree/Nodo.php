<?php 
class Nodo {
    private mixed $valor;
    private $filhos = []; 

    public function __construct($valor) {
        $this->valor = $valor;
    }

    public function adicionarFilho(Nodo $filho) {
        $this->filhos[] = $filho;
    }

    public function removerFilho($valor) {
        foreach ($this->filhos as $indice => $filho) {
            if ($filho->valor === $valor) {
                unset($this->filhos[$indice]);
                $this->filhos = array_values($this->filhos); 
                return true;
            }
        }
        return false;
    }

    public function getValor():mixed{
        return $this->valor;
    }

    public function setValor(mixed $valor){
        $this->valor = $valor;
    }

    public function getFilhos():array{
        return $this->filhos;
    }

    public function setFilhos(array $filhos){
        $this->filhos = $filhos;
    }
}