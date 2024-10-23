<?php

class AnalisadorSintaticoDescenteRecursive {
    private $cont = 0;
    public $lexico;

    public function __construct(Lexical $lexico) {
        $this->lexico = $lexico;
    }

    public function Programa() {
        if ($this->term('PROGRAM') &&
            $this->term('IDENTIFICADORES') && 
            $this->term('ABREPAREN') && 
            $this->term('FECHAPAREN') && 
            $this->term('ABRECHAVES') && 
            $this->Lista_comandos() && 
            $this->term('FECHACHAVES')) {
            $this->erros = [];
            return true;
        }
        return false;
    }

    public function Lista_var() {
        if ($this->Var()) {
            return $this->Lista_var(); 
        }
        return $this->vazio(); 
    }

    public function Var() {
        return $this->Tipo() && $this->term('IDENTIFICADORES') && $this->term('PONTOVIRGULA');
    }

    public function Tipo() {
        return $this->term('INT') || $this->term('CHAR') || $this->term('FLOAT') || $this->term('ARRAY');
    }

    public function Lista_comandos() {
        if ($this->Comando()) {
            return $this->Lista_comandos();
        }
        return $this->vazio(); // Retorna true se vazio
    }

    public function Comando() {
        return $this->Atribuicao() || 
               $this->Leitura() || 
               $this->Impressao() || 
               $this->Retorno() || 
               $this->ChamadaFuncao() || 
               $this->If() || 
               $this->For() || 
               $this->While();
    }

    public function Atribuicao() {
        return $this->term('IDENTIFICADORES') && 
               $this->term('IGUAL') && 
               $this->Expressao() && 
               $this->term('PONTOVIRGULA');
    }

    public function Leitura() {
        return $this->term('READ') && 
               $this->term('ABREPAREN') && 
               $this->term('IDENTIFICADORES') && 
               $this->term('FECHAPAREN') && 
               $this->term('PONTOVIRGULA');
    }

    public function Impressao() {
        return $this->term('PRINT') && 
               $this->term('ABREPAREN') && 
               $this->Expressao() && 
               $this->term('FECHAPAREN') && 
               $this->term('PONTOVIRGULA');
    }

    public function Retorno() {
        return $this->term('RETURN') && 
               $this->Expressao() && 
               $this->term('PONTOVIRGULA');
    }

    public function ChamadaFuncao() {
        return $this->term('IDENTIFICADORES') && 
               $this->term('ABREPAREN') && 
               $this->Expressao() && 
               $this->term('FECHAPAREN') &&
               $this->term('ABRECHAVES') &&
               $this->Comando() &&
               $this->term('FECHACHAVES');
    }

    public function If() {
        return $this->term('IF') && 
               $this->term('ABREPAREN') && 
               $this->Expressao() && 
               $this->term('FECHAPAREN') && 
               $this->term('ABRECHAVES') && 
               $this->Comando() && 
               $this->term('FECHACHAVES');
    }

    public function For() {
        return $this->term('FOR') && 
               $this->term('ABREPAREN') && 
               $this->Atribuicao() &&  
               $this->Expressao() && 
               $this->term('PONTOVIRGULA') && 
               $this->Atribuicao() && 
               $this->term('FECHAPAREN') && 
               $this->term('ABRECHAVES') && 
               $this->Comando() && 
               $this->term('FECHACHAVES');
    }

    public function While() {
        return $this->term('WHILE') && 
               $this->term('ABREPAREN') && 
               $this->Expressao() && 
               $this->term('FECHAPAREN') && 
               $this->term('ABRECHAVES') && 
               $this->Comando() && 
               $this->term('FECHACHAVES');
    }

    public function Expressao() {
        if ($this->Termo()) {
            while ($this->term('MAIS') || 
                $this->term('MENOS') || 
                $this->OperadorLogico()) {
                $this->Termo();
            }
            $this->erros = [];
            return true;
        }
        return false;
    }

    public function OperadorLogico() {
        return $this->term('IGUAL') || 
            $this->term('DIFERENTE') || 
            $this->term('MENOR_QUE') || 
            $this->term('MAIOR_QUE') || 
            $this->term('MENOR_OU_IGUAL') || 
            $this->term('MAIOR_OU_IGUAL') || 
            $this->term('EXCLA');
    }

    public function Termo() {
        if ($this->Fator()) {
            while ($this->term('ASTERISTICO') || $this->term('DIVISAO')) {
                $this->Fator();
            }
            $this->erros = [];
            return true;
        }
        return false;
    }

    public function Fator() {
        return $this->term('IDENTIFICADORES') || 
               $this->term('CONSTANTE') || 
               ($this->term('ABREPAREN') && $this->Expressao() && $this->term('FECHAPAREN'));
    }

    private $erros = [];

    public function term($tk) {
        $tokens = $this->lexico->validate(false)['resp']['tokens'];
        if(isset($tokens[$this->cont])){
            if (strtolower($tk) == $tokens[$this->cont]->getName(true)) {
                $this->cont++;
                $erros[]=[];
                return true;
            }else{
                $this->erros[] = "Erro: Esperado '$tk', encontrado '{$tokens[$this->cont]->getName(true)}'";
            }
        }else{
            $this->erros[] = "Erro: Esperado '$tk', encontrado 'vazio'";
        }
        return false;
    }

    public function getErros() {
        return $this->erros;
    }

    public function vazio() {
        return true; 
    }
}
?>
