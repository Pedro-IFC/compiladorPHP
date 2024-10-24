<div class="text">
    <h2>CLASSES</h2>
    <h3>class Lexical</h3>
    <div class="programminblock">
        //Iniciação <br>
        $lex = new Lexical(string $string); <br>
        $lex->validate(); <br>
        Retorno:  <br>
        [ <br>
            'sucess' => Automato::validate($this->chars, $Lexical) // Valida através do automato, <br>
            'tokens' =>  Automato::parseTokens($this->chars, $Lexical)['tokenList'] // TokenList da análise do automato, <br>
            'erros' =>  Automato::parseTokens($this->chars, $Lexical)['errorsList'] // Erros da análise do do automato, <br>
        ] <br>
    </div>
    <h3>class Automato</h3>
    <div class="programminblock">
        //Iniciação <br>
        Automato::validate(chars $chars, array $tabelaLexica) <br>
        Retorno: bool, variando se a string é válida. <br>
        Automato::parseTokens($this->chars, $Lexical) <br>
        Retorno: [
            'tokens'=> Tokens da análise léxica,
            'erros'=> Erros da análise léxica
        ]
    </div>
    <h3>class Token</h3>
    <div class="programminblock">
        //Iniciação <br>
        $tok = new Token(string $name, string $lexeme, int $inicio, int $fim); <br>
        $tok->getName() : string; <br>
        $tok->setName(string $name); <br>
        $tok->getLexeme() : string; <br>
        $tok->setLexeme(string $lexeme); <br>
        $tok->getInicio() : int; <br>
        $tok->setInicio(int $inicio); <br>
        $tok->setFim(int $fim); <br>
        $tok->getFim() : int; <br>
    </div>
    <h2>ROTAS</h2>
    <h3 class="pd0">POST</h3>
    <h3>domain/lexical/validate/</h3>
    <div class="programminblock">
        curl --location 'http://localhost:8181/lexical/validate' \ <br>
        --form 'string="se (a==b){escreva(c)}"'
    </div>
    <p>Assim obtemos o retorno: </p>
    <div class="programminblock">
{ <br>
    "sucess": true, <br>
    "tokens": [ <br>
        { <br>
            "name": "PALAVRASRESERVADAS", <br>
            "lexeme": "se", <br>
            "startPos": 0, <br>
            "finalPos": 2 <br>
        }, <br>
        { <br>
            "name": "OPERADORES", <br>
            "lexeme": "(", <br>
            "startPos": 2, <br>
            "finalPos": 4 <br>
        }, <br>
        { <br>
            "name": "IDENTIFICADORES", <br>
            "lexeme": "a", <br>
            "startPos": 3, <br>
            "finalPos": 5 <br>
        }, <br>
        { <br>
            "name": "OPERADORES", <br>
            "lexeme": "==", <br>
            "startPos": 4, <br>
            "finalPos": 7 <br>
        }, <br>
        { <br>
            "name": "IDENTIFICADORES", <br>
            "lexeme": "b", <br>
            "startPos": 6, <br>
            "finalPos": 8 <br>
        }, <br>
        { <br>
            "name": "OPERADORES", <br>
            "lexeme": ")", <br>
            "startPos": 7, <br>
            "finalPos": 9 <br>
        }, <br>
        { <br>
            "name": "OPERADORES", <br>
            "lexeme": "{", <br>
            "startPos": 8, <br>
            "finalPos": 10 <br>
        }, <br>
        { <br>
            "name": "PALAVRASRESERVADAS", <br>
            "lexeme": "escreva", <br>
            "startPos": 9, <br>
            "finalPos": 17 <br>
        }, <br>
        { <br>
            "name": "OPERADORES", <br>
            "lexeme": "(", <br>
            "startPos": 16, <br>
            "finalPos": 18 <br>
        }, <br>
        { <br>
            "name": "IDENTIFICADORES", <br>
            "lexeme": "c", <br>
            "startPos": 17, <br>
            "finalPos": 19 <br>
        }, <br>
        { <br>
            "name": "OPERADORES", <br>
            "lexeme": ")", <br>
            "startPos": 18, <br>
            "finalPos": 20 <br>
        }, <br>
        { <br>
            "name": "OPERADORES", <br>
            "lexeme": "}", <br>
            "startPos": 19, <br>
            "finalPos": 20 <br>
        } <br>
    ], <br>
    "erros": [] <br>
} <br>
    </div>
</div>