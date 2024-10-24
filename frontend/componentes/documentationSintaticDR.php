<div class="text">
    <h2>CLASSES</h2>
    <h3>AnalisadorSintaticoDescenteRecursive</h3>
    <br>
        A classe <code>AnalisadorSintaticoDescenteRecursive</code> é responsável por realizar a análise sintática de um programa, verificando se os tokens recebidos seguem a gramática da linguagem definida. O analisador é do tipo descendente recursivo, implementando a análise através de chamadas recursivas às produções gramaticais.
    <br>
        <h3>Métodos da Classe</h3>
        
        <h5>__construct(Lexical $lexico)</h5>
        <div class="programminblock"> 
        Construtor que inicializa o analisador sintático com uma instância da classe <code>Lexical</code>.
        </div>
        
        <h5>imprimirArvore()</h5>
        <div class="programminblock">   
        Imprime a árvore de derivação, caso a análise seja bem-sucedida. Caso contrário, imprime uma mensagem indicando que a árvore não foi gerada.
        </div>
        <h5>analisar() : bool</h5>
        <div class="programminblock">   
        Método principal da análise. Inicia o processo de verificação sintática a partir da produção <code>Programa</code> ou da <code>Lista_comandos</code>.
        </div>
</div>

<h2>ROTAS</h2>
    <h3 class="pd0">POST</h3>
    <h3>domain/lexical/validate/</h3>
    <div class="programminblock">
        curl --location 'http://localhost:8282/sintaticRecursiveDescentSyntax/validate/' \ <br>
        --form 'string="programa a(){se(a>=2){escreva(a);}}"'
    </div>
    <p>Assim obtemos o retorno: </p>
    <div class="programminblock">
    {<br>
        "result": true,<br>
        "tree": "Programa\n--program\n--identificadores\n--abreparen\n--fechaparen\n--abrechaves\n--Lista_comandos\n----Comando\n------If\n--------if\n--------abreparen\n--------Expressao\n----------Termo\n------------identificadores\n----------OperadorLogico\n------------maior_ou_igual\n----------Termo\n------------constinteiras\n--------fechaparen\n--------abrechaves\n--------Comando\n----------Impressao\n------------write\n------------abreparen\n------------Expressao\n--------------Termo\n----------------identificadores\n------------fechaparen\n------------pontovirgula\n--------fechachaves\n--fechachaves\n"
    <br>}<br>
    </div>
</div>
