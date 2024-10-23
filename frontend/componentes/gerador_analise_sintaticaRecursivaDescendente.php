<?php 
    $AnalisadorDR = New AnalisadorSintaticoDescenteRecursive(new Lexical("programa a( int ){
    }"));
    var_dump($AnalisadorDR->Programa());
    var_dump($AnalisadorDR->getErros());
?>