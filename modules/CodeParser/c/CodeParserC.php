<?php
include_once 'TransformationTableC.php';

class CodeParserC implements CodeParser {
    private array $tokenTable;
    public function __construct(){
        $this->tokenTable = TransformationTableC::getTable();
    }
    public function generateCode($tokenlist): string {
        $code = "";

        foreach ($tokenlist as $token) {
            $name = $token->getName(true); // Convert token name to lowercase
            $lexeme = $token->getLexeme();

            if (array_key_exists($name, $this->tokenTable)) {
                $code .= $this->tokenTable[$name];
            } elseif (in_array($name, ['id', 'constantesinteiras', 'constantespontoflutuante', 'strliteral', 'int', 'char', 'float'])) {
                $code .= " $lexeme ";
            } 
        }

        return $code;
    }
}