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
                        $this->errors[] = "Variable '{$symbol['name']}' declared in global scope and in scope '{$symbol['scope']}', which is not allowed.";
                    }
                }
            }
        }
    }

    private function checkTypeConsistency()
    {
        foreach ($this->symbolTable as $symbol) {
            if ($symbol['category'] === 'variable' && !in_array($symbol['type'], ['INT', 'FLOAT', 'STRING', 'CHAR'])) {
                $this->errors[] = "Invalid type '{$symbol['type']}' for variable '{$symbol['name']}'.";
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
                    $this->errors[] = "Variable '{$varName}' used but not declared at line {$token->getLine()}.";
                }
            }
        }
    }

    private function checkInvalidAssignments()
    {
        foreach ($this->tokens as $index => $token) {
            if ($token->getName() === 'ID' && $this->tokens[$index + 1]->getName()=="EQ") {
                $varName = $token->getLexeme();
                $assignmentValue = $this->tokens[$index + 2]->getLexeme();                
                if($this->tokens[$index + 3]->getName()=="AP"){
                    $assignmentValue = $this->getVariableType($assignmentValue, $this->currentScope);
                }
                $varType = $this->getVariableType($varName, $this->currentScope);
                if ($varType && !$this->isValidAssignment($varType, $assignmentValue)) {
                    $this->errors[] = "Invalid assignment of value '{$assignmentValue}' to variable '{$varName}' of type '{$varType}' at line {$token->getLine()}.";
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
            'STRING' => '/^".*"$/',
            'CHAR' => "/^'.'$/",
        ];
        return isset($typePatterns[$type]) && preg_match($typePatterns[$type], $value);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}