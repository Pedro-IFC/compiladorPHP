<?php
include_once 'TransformationTableC.php';

class CodeParserC implements CodeParser {
    private array $transformationTable;
    private array $symbolTable;

    public function generateCode($derivationTree, $symbolTable): string {
        $this->symbolTable = $symbolTable;
        return $this->processNode($derivationTree[0]);
    }

    public function __construct(){
        $this->transformationTable = TransformationTableC::getTable();
    }

    private function processNode($node): string {
        $code = "";
        if (!is_array($node) || empty($node)) {
            return '';
        }
    
        // Se o nó for terminal, trata o valor diretamente
        if ($node['type'] === 'terminal') {
            $symbol = $this->findSymbol($node['value']->getLexeme());
            
            // Se o valor for uma função, gera o código da função
            if ($symbol && $symbol['category'] === 'function') {
                return "function " . $symbol['name'] . "() {\n";
            }
            // Caso contrário, retorna o valor do terminal conforme a tabela de transformação
            return ($this->transformationTable[$node['value']->getName()] ?? $node['value']->getLexeme()) . " ";
        } else {
            // Verifica o tipo de nó não terminal
            switch ($node['value']) {
                case 'Programa':
                    // O nó representa o programa, então processa todos os comandos dentro dele
                    foreach ($node['children'] as $child) {
                        $code .= $this->processNode($child);
                    }
                    break;
    
                case 'Funcao':
                    // Se o nó for uma função, trata como a definição de uma função
                    $code .= $this->processFunction($node);
                    break;
    
                case 'ChamadaFuncao':
                    // Se o nó for uma chamada de função, gera o código de chamada
                    $code .= $this->processFunctionCall($node);
                    break;
    
                case 'Declaracao':
                    // Se o nó for uma declaração de variável
                    $code .= $this->processDeclaration($node);
                    break;
    
                case 'Atribuicao':
                    // Se o nó for uma atribuição
                    $code .= $this->processAtribuicao($node);
                    break;
    
                // Adicione outros casos conforme necessário (ControleFluxo, Expressao, etc.)
                default:
                    // Caso padrão, processa os filhos do nó
                    foreach ($node['children'] as $child) {
                        $code .= $this->processNode($child);
                    }
            }
        }
    
        return $code;
    }
    
    // Função para processar a declaração de função
    private function processFunction($node): string {
        $code = "int " . $node['children'][1]['value'] . "() {\n";
        foreach ($node['children'][3]['children'] as $command) {
            $code .= $this->processNode($command);
        }
        $code .= "\n}";
        return $code;
    }
    
    // Função para processar a chamada de função
    private function processFunctionCall($node): string {
        $args = "";
        foreach ($node['children'] as $child) {
            $args .= $this->processNode($child);
        }
        return $node['children'][0]['value'] . "(" . $args . ");\n";
    }
    
    // Função para processar declaração de variáveis
    private function processDeclaration($node): string {
        return $node['children'][0]['value'] . " " . $node['children'][1]['value'] . ";\n";
    }
    
    // Função para processar atribuições
    private function processAtribuicao($node): string {
        $varName = $node['children'][0]['value'];
        $value = $this->processNode($node['children'][1]);
        return $varName . " = " . $value . ";\n";
    }
    
    // Função para encontrar um símbolo na tabela de símbolos
    private function findSymbol($name) {
        foreach ($this->symbolTable as $symbol) {
            if ($symbol['name'] === $name) {
                return $symbol;
            }
        }
        return null;
    }
    
    
}
