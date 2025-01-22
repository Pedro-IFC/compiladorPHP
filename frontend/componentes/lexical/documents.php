<div class="text">
    <h2>CLASSES</h2>
    <h3>class Lexical</h3>
    <div class="programminblock">
        //Iniciação <br>
        $lex = new Lexical(string $string); <br>
        $lex->validate(); <br>
        Retorno:  <br>
        [ <br>
            'sucess' => AnalisadorLexicovalidate($this->chars, $Lexical) // Valida através do automato, <br>
            'tokens' =>  AnalisadorLexicoparseTokens($this->chars, $Lexical)['tokenList'] // TokenList da análise do automato, <br>
            'erros' =>  AnalisadorLexicoparseTokens($this->chars, $Lexical)['errorsList'] // Erros da análise do do automato, <br>
        ] <br>
    </div>
    <h3>class Automato</h3>
    <div class="programminblock">
        //Iniciação <br>
        AnalisadorLexicovalidate(chars $chars, array $tabelaLexica) <br>
        Retorno: bool, variando se a string é válida. <br>
        AnalisadorLexicoparseTokens($this->chars, $Lexical) <br>
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
</div>