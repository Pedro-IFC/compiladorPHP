<?php  

interface CodeParser{     
    public function generateCode( array $tokenList): string; 
}