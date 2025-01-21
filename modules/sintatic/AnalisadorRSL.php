<?php

class SLRParser {
    private array $gotoTable;
    private array $actionTable;
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
                throw new Exception("Erro de sintaxe na linha " . $token->getLine() . ", token inesperado: " . $tokenName);
            }
    
            $action = $this->actionTable[$state][$tokenName];
    
            if ($action['type'] === 'SHIFT') {
                $stack[] = $action['state'];
                $inputPointer++;
            } elseif ($action['type'] === 'REDUCE') {
                $rule = $this->productions[$action['rule']];
    
                for ($i = 0; $i < $rule[1]; $i++) {
                    array_pop($stack);
                }

                $topState = end($stack);

                $gotoState = $this->gotoTable[$topState][$rule[0]] ?? null;
    
                if ($gotoState === null) {
                    throw new Exception("Erro ao aplicar redução na linha " . $token->getLine());
                }

                $stack[] = $gotoState;
            } elseif ($action['type'] === 'ACCEPT') {
                return true;
            }
        }
    }
    
}
