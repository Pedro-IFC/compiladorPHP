<?php include './frontend/componentes/header.php'; ?>
    <div class="home">
        <div class="al-container">
            <h2>Gramática</h2>
            <div class="row dif noscroll">
                <div class="small">
                    <h3>Não terminais</h3>
                    <div class="text">
                        <div class="programminblock"> 
                            <ul class="disc">
                                <li>Programa</li><br>
                                <li>Lista_var</li><br>
                                <li>Var</li><br>
                                <li>Tipo</li><br>
                                <li>Lista_comandos</li><br>
                                <li>Comando</li><br>
                                <li>Atribuicao</li><br>
                                <li>Leitura</li><br>
                                <li>Impressao</li><br>
                                <li>Retorno</li><br>
                                <li>ChamadaFuncao</li><br>
                                <li>If</li><br>
                                <li>For</li><br>
                                <li>While</li><br>
                                <li>Expressao</li><br>
                                <li>OperadorLogico</li><br>
                                <li>Termo</li><br>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="medium">
                    <h3>Terminais/Tokens</h3>
                    <div class="text">
                        <div class="programminblock"> 
                            <ul >
                                <li><b>PROGRAM:</b> programa</li><br>
                                <li><b>IDENTIFICADORES:</b> Representa identificadores variáveis ou de funções</li><br>
                                <li><b>ABREPAREN:</b> (</li><br>
                                <li><b>FECHAPAREN:</b> )</li><br>
                                <li><b>ABRECHAVES:</b> {</li><br>
                                <li><b>FECHACHAVES:</b> }</li><br>
                                <li><b>PONTOVIRGULA:</b> ;</li><br>
                                <li><b>INT, CHAR, FLOAT, ARRAY:</b> Tipos de dados</li><br>
                                <li><b>IGUAL:</b> =</li><br>
                                <li><b>READ:</b> leitura</li><br>
                                <li><b>WRITE:</b> impressão</li><br>
                                <li><b>RETURN:</b> retorno</li><br>
                                <li><b>IF:</b> condicional if</li><br>
                                <li><b>FOR:</b> laço for</li><br>
                                <li><b>WHILE:</b> laço while</li><br>
                                <li><b>DUPLAIGUAL:</b> ==</li><br>
                                <li><b>DIFERENTE:</b> !=</li><br>
                                <li><b>MENOR_QUE:</b> <</li><br>
                                <li><b>MAIOR_QUE:</b> ></li><br>
                                <li><b>MENOR_OU_IGUAL:</b> <=</li><br>
                                <li><b>MAIOR_OU_IGUAL:</b> >=</li><br>
                                <li><b>CONSTINTEIRAS:</b> Números inteiros</li><br>
                                <li><b>CONSTFLUTUANTE:</b> Números de ponto flutuante </li><br>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="big">
                    <h3>Gramática</h3>
                    <div class="text">
                        <div class="programminblock"> 
                            <ul>
                                <li><b>Programa</b> ::= PROGRAM IDENTIFICADORES ABREPAREN FECHAPAREN ABRECHAVES Lista_comandos FECHACHAVES</li><br>
                                <li><b>Lista_var</b> ::= Var Lista_var | ε</li><br>
                                <li><b>Var</b> ::= Tipo IDENTIFICADORES PONTOVIRGULA</li><br>
                                <li><b>Tipo</b> ::= INT | CHAR | FLOAT | ARRAY</li><br>
                                <li><b>Lista_comandos</b> ::= Comando Lista_comandos | ε</li><br>
                                <li><b>Comando</b> ::= Atribuicao | Leitura | Impressao | Retorno | ChamadaFuncao | If | For | While</li><br>
                                <li><b>Atribuicao</b> ::= IDENTIFICADORES IGUAL Expressao PONTOVIRGULA</li><br>
                                <li><b>Leitura</b> ::= READ ABREPAREN IDENTIFICADORES FECHAPAREN PONTOVIRGULA</li><br>
                                <li><b>Impressao</b> ::= WRITE ABREPAREN (Expressao | IDENTIFICADORES) FECHAPAREN PONTOVIRGULA</li><br>
                                <li><b>Retorno</b> ::= RETURN Expressao PONTOVIRGULA</li><br>
                                <li><b>ChamadaFuncao</b> ::= IDENTIFICADORES ABREPAREN Expressao FECHAPAREN ABRECHAVES Comando FECHACHAVES</li><br>
                                <li><b>If</b> ::= IF ABREPAREN (Expressao | IDENTIFICADORES) FECHAPAREN ABRECHAVES Comando FECHACHAVES</li><br>
                                <li><b>For</b> ::= FOR ABREPAREN Atribuicao Expressao PONTOVIRGULA Atribuicao FECHAPAREN ABRECHAVES Comando FECHACHAVES</li><br>
                                <li><b>While</b> ::= WHILE ABREPAREN (Expressao | IDENTIFICADORES) FECHAPAREN ABRECHAVES Comando FECHACHAVES</li><br>
                                <li><b>Expressao</b> ::= Termo {OperadorLogico Termo}</li><br>
                                <li><b>OperadorLogico</b> ::= DUPLAIGUAL | DIFERENTE | MENOR_QUE | MAIOR_QUE | MENOR_OU_IGUAL | MAIOR_OU_IGUAL</li><br>
                                <li><b>Termo</b> ::= IDENTIFICADORES | CONSTINTEIRAS | CONSTFLUTUANTE</li><br>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
<?php include './frontend/componentes/footer.php'; ?>