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
        return empty($this->errors);
    }

    private function checkVariableScope()
    {
        $globalVariables = [];
        foreach ($this->symbolTable as $symbol) {
            if ($symbol['category'] === 'variable') {
                if (strpos($symbol['scope'], 'global') !== false) {
                    $globalVariables[$symbol['name']] = $symbol['scope'];
                } else {
                    if (isset($globalVariables[$symbol['name']])) {
                        $this->errors[] = "Variável '{$symbol['name']}' declarada no escopo global e no escopo '{$symbol['scope']}', o que não é permitido.";
                    }
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
                if($this->tokens[$index + 3]->getName()=="AP"){
                    $assignmentValue = $this->getVariableType($assignmentValue, $this->currentScope);
                }
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