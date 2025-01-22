<?php

class AnalisadorSRL {
    private array $gotoTable;
    private array $actionTable;
    private DerivationTree $derivationTree;
    private array $errors = [];

    private $productions = [
        ["<Programa>", 6], ["<Programa>", 0], ["<ListaParametros>", 3], ["<ListaParametros>", 0], ["<ListaParametrosRest>", 4], ["<ListaParametrosRest>", 0], ["<ListaComandos>", 2], ["<ListaComandos>", 0],
        ["<ListaComandosRest>", 2], ["<ListaComandosRest>", 0], ["<Comando>", 1], ["<Comando>", 1], ["<Comando>", 1], ["<Comando>", 1], ["<Comando>", 1], ["<Declaracao>", 3],
        ["<Atribuicao>", 4], ["<ChamadaFuncao>", 5], ["<ControleFluxo>", 11], ["<ControleFluxo>", 7], ["<ControleFluxo>", 13], ["<ControleFluxo>", 7], ["<Imprime>", 5], ["<ArgumentoImpressao>", 1],
        ["<ArgumentoImpressao>", 1], ["<ArgumentoImpressao>", 3], ["<Expressao>", 2], ["<Expressao>", 1], ["<ExpressaoRest>", 3], ["<ExpressaoRest>", 3], ["<ExpressaoRest>", 0], ["<ExpressaoLogica>", 2],
        ["<ExpressaoLogicaRest>", 3], ["<ExpressaoLogicaRest>", 3], ["<ExpressaoLogicaRest>", 3], ["<ExpressaoLogicaRest>", 3], ["<ExpressaoLogicaRest>", 3], ["<ExpressaoLogicaRest>", 3], ["<ExpressaoLogicaRest>", 3], ["<ExpressaoLogicaRest>", 0],
        ["<Termo>", 2], ["<TermoRest>", 3], ["<TermoRest>", 3], ["<TermoRest>", 3], ["<TermoRest>", 0], ["<Fator>", 1], ["<Fator>", 1], ["<Fator>", 1],
        ["<Fator>", 3], ["<Tipo>", 1], ["<Tipo>", 1], ["<Tipo>", 1], ["<ListaArgumentos>", 2], ["<ListaArgumentos>", 0], ["<ListaArgumentosRest>", 3], ["<ListaArgumentosRest>", 0],
        [64, 1], [64, 1], [64, 1],
    ];

    public function __construct(string $jsonFilePath) {
        $this->loadParsingTable($jsonFilePath);
        $this->derivationTree = new DerivationTree();
    }

    public function getDerivationTree(){
        return $this->derivationTree;
    }

    public function getErrors(){
        return $this->errors;
    }

    private function loadParsingTable(string $filePath): void {
        $data = json_decode(file_get_contents($filePath), true);
        if ($data === null) {
            throw new Exception("Invalid JSON file");
        }

        $this->gotoTable = $data['goto'];
        $this->actionTable = $data['actionTable'];
    }

    public function parse(array $tokens): bool {
        $stack = [0];
        $inputPointer = 0;
        while (true) {
            $state = end($stack);
            $token = $tokens[$inputPointer] ?? new Token('$', '$', 0, 0);
            $tokenName = $token->getName();

            if (!isset($this->actionTable[$state][$tokenName])) {
                $this->errors[] = "Erro de sintaxe na linha " . $token->getLine() . ", token inesperado: " . $token->getLexeme();
                return false;
            }

            $action = $this->actionTable[$state][$tokenName];

            if ($action['type'] === 'SHIFT') {
                $stack[] = $action['state'];
                
                $this->derivationTree->pushTerminal($token);

                $inputPointer++;
            } elseif ($action['type'] === 'REDUCE') {
                $rule = $this->productions[$action['rule']];
                $nonTerminal = $rule[0];
                $productionLength = $rule[1];
                for ($i = 0; $i < $productionLength; $i++) {
                    array_pop($stack);
                }

                $topState = end($stack);
                $gotoState = $this->gotoTable[$topState][$nonTerminal] ?? null;

                if ($gotoState === null) {
                    $this->errors[] = "Erro ao aplicar redução na linha " . $token->getLine();
                    return false;
                }

                $stack[] = $gotoState;

                $this->derivationTree->reduce(str_replace(">","", str_replace("<","", $nonTerminal)), $productionLength);
            } elseif ($action['type'] === 'ACCEPT') {
                return true;
            }
        }
    }
}
