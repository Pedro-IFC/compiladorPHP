<?php 

class NTree {
    public $raiz; 

    public function __construct($valorRaiz) {
        $this->raiz = new Nodo($valorRaiz);
    }

    public function buscar(Nodo $no, $valor) {
        if ($no->getValor() === $valor) {
            return $no;
        }
        foreach ($no->getFilhos() as $filho) {
            $resultado = $this->buscar($filho, $valor);
            if ($resultado !== null) {
                return $resultado;
            }
        }
        return null; 
    }
}
