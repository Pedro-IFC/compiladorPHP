<div class="text">
    <h2>CLASSES</h2>
    <h3>class AnalisadorSRL</h3>
    <div class="programminblock">
        //Iniciação <br>
        $SLR = new AnalisadorSRL("./data/sintatic/tabelagoto.json"); <br>
        $SLR->parse($lexicalC->getTokenList()); <br>
        Retorno: true or false<br>
    </div>
    <h3>class DerivationTree</h3>
    <div class="programminblock">
    //Iniciação <br>
    $derivationTree = new DerivationTree();<br>
    $derivationTree->pushTerminal($token);<br>
    $derivationTree->reduce($nonTerminal, $productionLength);<br>
    </div>
    <h2>Funções</h2>
    <h3>function printNode()</h3>
    <div class="programminblock">
        //Call <br>
        printNode($SLR->getDerivationTree()->getStack()[0]);<br>
        //Response <br>
        Nó: Programa <br>
        -Nó: ListaComandos <br>
        --Token: MAIN (Lexema: "main") <br>
        --Token: AP (Lexema: "(") <br>
        --Token: FP (Lexema: ")") <br>
        --Token: AC (Lexema: "{") <br>
        -Token: FC (Lexema: "}") <br>
    </div>
</div>