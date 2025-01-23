<?php

class SemanticAnalyser {
    private SymbolTable $symbolTable;
    private array $errors = [];

    public function getErrors(){
        return $this->errors;
    }
    
    public function __construct() {
        $this->symbolTable = new SymbolTable();
    }

    public function analyse($node) {
        $this->traverseTree($node);
    }

    private function traverseTree($node) {
        if ($node instanceof Token) {
            $this->do_action($node);
        } else {
            if(isset($node->children)){
                foreach ($node->children as $child) {
                    $this->traverseTree($child);
                }
            }
            $this->do_production_action($node);
        }
    }
    private function do_action($token) {
        var_dump($token);
        switch($token->getName()){
            case "";
        }
    }
    private function do_production_action($production) {
        switch($production){
            case "";
        }
    }
}
