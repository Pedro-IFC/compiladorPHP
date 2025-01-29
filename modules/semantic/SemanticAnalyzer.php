<?php

class SemanticAnalyzer
{
    private $symbolTable;
    private $tokens;
    private $errors = [];
    private $currentScope;

    public function __construct(array $symbolTable, array $tokens)
    {
        $this->symbolTable = $symbolTable;
        $this->tokens = $tokens;
        $this->currentScope = 'global0';
    }

    public function analyze()
    {
        $this->checkVariableScope();
        $this->checkTypeConsistency();
        $this->checkVariableUsage();
        $this->checkInvalidAssignments();
        $this->checkReturnValues();
        return empty($this->errors);
    }
    private function checkReturnValues()
    {
        $pilhafuncoes =[];
        foreach ($this->tokens as $index => $token) {
            if ($token->getName() === 'RETORNO') {
                $returnValue = $this->tokens[$index + 1]->getLexeme();
                $function = array_pop($pilhafuncoes);    
                foreach($this->symbolTable as $symbol){
                    if($symbol['name']==$returnValue){
                        $returnValue = $symbol['type'];
                    }
                }
                if ($function) {
                    if (!$this->isValidAssignment($function['type'], $returnValue)) {
                        $this->errors[] = "Retorno inválido '{$returnValue}' na função '{$function['name']}' do tipo '{$function['type']}' na linha {$token->getLine()}.";
                    }
                } else {
                    $this->errors[] = "Retorno fora de um escopo de função na linha {$token->getLine()}.";
                }
            }else if($token->getName() === 'ID'){
                foreach ($this->symbolTable as $symbol) {
                    if ($symbol['category'] === 'function' && $symbol['name'] === $token->getLexeme()) {
                        $pilhafuncoes[] = $symbol;
                    }
                }
            }
        }
    }
    
    private function checkVariableScope()
    {
        $scopedVariables = [];

        foreach ($this->symbolTable as $symbol) {
            if ($symbol['category'] === 'variable') {
                $scope = $symbol['scope'];
                $name = $symbol['name'];

                // Verifica se a variável já foi declarada no mesmo escopo
                if (!isset($scopedVariables[$scope])) {
                    $scopedVariables[$scope] = [];
                }

                if (in_array($name, $scopedVariables[$scope])) {
                    $this->errors[] = "Variável '{$name}' declarada mais de uma vez no escopo '{$scope}' na linha {$symbol['line']}.";
                } else {
                    $scopedVariables[$scope][] = $name;
                }
            }
        }
    }

    private function checkTypeConsistency()
    {
        foreach ($this->symbolTable as $symbol) {
            if ($symbol['category'] === 'variable' && !in_array($symbol['type'], ['INT', 'FLOAT', 'STRING', 'CHAR'])) {
                $this->errors[] = "Tipo inválido '{$symbol['type']}' para a variável '{$symbol['name']}'.";
            }
        }
    }

    private function checkVariableUsage()
    {
        $declaredVariables = [];
        foreach ($this->symbolTable as $symbol) {
            $declaredVariables[$symbol['name']] = $symbol['scope'];
        }

        foreach ($this->tokens as $token) {
            if ($token->getName() === 'ID') {
                $varName = $token->getLexeme();
                if (!isset($declaredVariables[$varName])) {
                    $this->errors[] = "Variável '{$varName}' utilizada, mas não declarada, na linha {$token->getLine()}.";
                }
            }
        }
    }

    private function checkInvalidAssignments()
    {
        foreach ($this->tokens as $index => $token) {
            if ($token->getName() === 'ID' && $this->tokens[$index + 1]->getName()=="EQ") {
                foreach($this->symbolTable as $symbol){
                    if($symbol['name']==$token->getLexeme()){
                        $this->currentScope = $symbol['scope'];
                    }
                }
                $varName = $token->getLexeme();
                $assignmentValue = $this->tokens[$index + 2]->getLexeme();         
                foreach($this->symbolTable as $symbol){
                    if($symbol['name']==$assignmentValue){
                        $assignmentValue = $symbol['type'];
                    }
                }
                $varType = $this->getVariableType($varName, $this->currentScope);
                if (!empty($varType) && !$this->isValidAssignment($varType, $assignmentValue)) {
                    $this->errors[] = "Atribuição inválida do valor '{$assignmentValue}' à variável '{$varName}' do tipo '{$varType}' na linha {$token->getLine()}.";
                }
            }
        }
    }

    private function getVariableType($varName, $scope)
    {
        foreach ($this->symbolTable as $symbol) {
            if ($symbol['name'] === $varName && $symbol['scope'] === $scope) {
                return $symbol['type'];
            }
        }
        return null;
    }

    private function isValidAssignment($type, $value)
    {
        $typePatterns = [
            'INT' => '/^\d+$/',
            'FLOAT' => '/^\d+\.\d+$/',
            'CHAR' => '/^".*"$/',
        ];
        if(isset($typePatterns[$value])){
            return $type==$value;
        }
        return isset($typePatterns[$type]) && preg_match($typePatterns[$type], $value);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}