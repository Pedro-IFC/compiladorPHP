<?php  

interface CodeParser{     
    public function generateCode($derivationTree, $symbolTable): string; 
}