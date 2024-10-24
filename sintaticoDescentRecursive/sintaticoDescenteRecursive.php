<?php
require_once "NoArvore.php";

class AnalisadorSintaticoDescenteRecursive {
    private int $cont = 0;
    private Lexical $lexico;
    private ?NoArvore $arvore = null;
    private array $erros = [];

    public function imprimirArvore() {
        if ($this->arvore !== null) {
            $this->arvore->imprimir();
        } else {
            echo "Árvore de derivação vazia ou não gerada.<br>";
        }
    }
    public function getArvore() : string{
        return $this->arvore->toString();
    }
    public function getErros():array{
        return $this->erros;
    }
    public function __construct(Lexical $lexico) {
        $this->lexico = $lexico;
    }
    public function analisar($inicio="programa"):bool{
        if($inicio=="programa"){
            return $this->Programa();
        }else{
            $no = new NoArvore("Lista de comandos");
            $r= $this->Lista_comandos($no);
            $this->arvore = $no; // Armazena a árvore completa
            return $r;

        }
    }
    public function Programa() {
        $no = new NoArvore("Programa");
        if ($this->term('PROGRAM', $no) &&
            $this->term('IDENTIFICADORES', $no) && 
            $this->term('ABREPAREN', $no) && 
            $this->term('FECHAPAREN', $no) && 
            $this->term('ABRECHAVES', $no) && 
            $this->Lista_comandos($no) && 
            $this->term('FECHACHAVES', $no)) {
            
            $this->arvore = $no; // Armazena a árvore completa
            return true;
        }
        return false;
    }

    public function Lista_var(NoArvore $pai) {
        $no = new NoArvore("Lista_var");
        if ($this->Var($no)) {
            $pai->adicionarFilho($no);
            return $this->Lista_var($pai); 
        }
        return $this->vazio(); 
    }

    public function Var(NoArvore $pai) {
        $no = new NoArvore("Var");
        if ($this->Tipo($no) && $this->term('IDENTIFICADORES', $no) && $this->term('PONTOVIRGULA', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function Tipo(NoArvore $pai) {
        $no = new NoArvore("Tipo");
        if ($this->term('INT', $no) || $this->term('CHAR', $no) || $this->term('FLOAT', $no) || $this->term('ARRAY', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function Lista_comandos(NoArvore $pai) {
        $no = new NoArvore("Lista_comandos");
        if ($this->Comando($no)) {
            $pai->adicionarFilho($no);
            return $this->Lista_comandos($pai);
        }
        return $this->vazio(); 
    }

    public function Comando(NoArvore $pai) {
        $no = new NoArvore("Comando");
        if ($this->Atribuicao($no) || 
            $this->Leitura($no) || 
            $this->Impressao($no) || 
            $this->Retorno($no) || 
            $this->ChamadaFuncao($no) || 
            $this->If($no) || 
            $this->For($no) || 
            $this->While($no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function Atribuicao(NoArvore $pai) {
        $no = new NoArvore("Atribuicao");
        if ($this->term('IDENTIFICADORES', $no) && 
            $this->term('IGUAL', $no) && 
            ($this->Expressao($no) || $this->term("CONSTINTEIRAS", $no) || $this->term("CONSTFLUTUANTE", $no)) && 
            $this->term('PONTOVIRGULA', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function Leitura(NoArvore $pai) {
        $no = new NoArvore("Leitura");
        if ($this->term('READ', $no) && 
            $this->term('ABREPAREN', $no) && 
            $this->term('IDENTIFICADORES', $no) && 
            $this->term('FECHAPAREN', $no) && 
            $this->term('PONTOVIRGULA', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function Impressao(NoArvore $pai) {
        $no = new NoArvore("Impressao");
        if ($this->term('WRITE', $no) && 
            $this->term('ABREPAREN', $no) && 
            ($this->Expressao($no) || $this->term('IDENTIFICADORES', $no)) && 
            $this->term('FECHAPAREN', $no) && 
            $this->term('PONTOVIRGULA', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function Retorno(NoArvore $pai) {
        $no = new NoArvore("Retorno");
        if ($this->term('RETURN', $no) && 
            $this->Expressao($no) && 
            $this->term('PONTOVIRGULA', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function ChamadaFuncao(NoArvore $pai) {
        $no = new NoArvore("ChamadaFuncao");
        if ($this->term('IDENTIFICADORES', $no) && 
            $this->term('ABREPAREN', $no) && 
            $this->Expressao($no) && 
            $this->term('FECHAPAREN', $no) &&
            $this->term('ABRECHAVES', $no) &&
            $this->Comando($no) &&
            $this->term('FECHACHAVES', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function If(NoArvore $pai) {
        $no = new NoArvore("If");
        if ($this->term('IF', $no) && 
            $this->term('ABREPAREN', $no) && 
            ($this->Expressao($no) || $this->term('IDENTIFICADORES', $no)) && 
            $this->term('FECHAPAREN', $no) && 
            $this->term('ABRECHAVES', $no) && 
            $this->Comando($no) && 
            $this->term('FECHACHAVES', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function For(NoArvore $pai) {
        $no = new NoArvore("For");
        if ($this->term('FOR', $no) && 
            $this->term('ABREPAREN', $no) && 
            $this->Atribuicao($no) &&  
            $this->Expressao($no) && 
            $this->term('PONTOVIRGULA', $no) && 
            $this->Atribuicao($no) && 
            $this->term('FECHAPAREN', $no) && 
            $this->term('ABRECHAVES', $no) && 
            $this->Comando($no) && 
            $this->term('FECHACHAVES', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function While(NoArvore $pai) {
        $no = new NoArvore("While");
        if ($this->term('WHILE', $no) && 
            $this->term('ABREPAREN', $no) && 
            ($this->Expressao($no) || $this->term('IDENTIFICADORES', $no))  && 
            $this->term('FECHAPAREN', $no) && 
            $this->term('ABRECHAVES', $no) && 
            $this->Comando($no) && 
            $this->term('FECHACHAVES', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function Expressao(NoArvore $pai) {
        $no = new NoArvore("Expressao");
        if ($this->Termo($no)) {
            while ($this->term('MAIS', $no) || 
                   $this->term('MENOS', $no) || 
                   $this->OperadorLogico($no)) {
                if(!$this->Termo($no)){
                    $this->erros[] = "Erro no analisador sintático: Esperado 'identificador/constante', encontrado 'vazio'.";
                    return false;
                }
            }
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function OperadorLogico(NoArvore $pai) {
        $no = new NoArvore("OperadorLogico");
        if ($this->term('DUPLAIGUAL', $no) || 
            $this->term('DIFERENTE', $no) || 
            $this->term('MENOR_QUE', $no) || 
            $this->term('MAIOR_QUE', $no)|| 
            $this->term('MENOR_OU_IGUAL', $no) || 
            $this->term('MAIOR_OU_IGUAL', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        $this->erros[] = "Erro no analisador sintático: Esperado 'operadorLogico', encontrado 'vazio'.";
        return false;
    }

    public function Termo(NoArvore $pai) {
        $no = new NoArvore("Termo");
        if ($this->term('IDENTIFICADORES', $no) || 
            $this->term('CONSTINTEIRAS', $no) || 
            $this->term('CONSTFLUTUANTE', $no)) {
            $pai->adicionarFilho($no);
            return true;
        }
        return false;
    }

    public function term($tk, NoArvore $pai) {
        $resp = $this->lexico->validate(false)['resp'];
        if (!empty($resp['errors'])) {
            $this->erros = $resp['errors'];
            return false;
        }
        $tokens = $resp['tokens'];
        if (isset($tokens[$this->cont])) {
            if (strtolower($tk) == $tokens[$this->cont]->getName(true)) {
                // Cria um nó para o token reconhecido e o adiciona ao nó pai
                $noToken = new NoArvore($tokens[$this->cont]->getName(true));
                $pai->adicionarFilho($noToken);

                $this->cont++;
                return true;
            }
        } else {
            $this->erros[] = "Erro no analisador sintático: Esperado '$tk', encontrado 'vazio'.";
        }
        return false;
    }

    public function vazio() {
        return true;
    }
}

?>
