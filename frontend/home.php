<?php 
include './frontend/componentes/header.php';

if(isset($_GET['string'])){
    $lexicalC = new Lexical($_GET['string']);
    $lexResp = $lexicalC->validate(false);
    $SLR = new AnalisadorSRL("./data/sintatic/tabelagoto.json");
    $analise = $SLR->parse($lexicalC->getTokenList());
    $semantic = new SemanticAnalyzer($SLR->getSymbolTable(), $lexicalC->getTokenList());
    $code = CodeGenerator::getInstance()::generate($lexicalC->getTokenList());
}
?>
    <div class="home">
        <div class="al-container">
            <h2>Compilador</h2>
            <div class="row">
                <div class="left">
                    <div class="form">
                        <form method="GET">
                            <textarea name="string" id="string_txt"><?php if(isset($_GET['string'])){echo $_GET['string']; }else{ ?>main(){
int dobro(int a){
    retorno a*2;
}
int a = 1;
se(a != 1){
    int c = dobro(a);
    imprima(c);
}senao{
    imprima("a e invalido");
}
}<?php } ?></textarea>
                            <div class="flex">
                                <button type="submit">Compilar</button>
                                <a href="./" class="red">
                                    <svg width="18px" height="18px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke="white" d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>  
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="right">
                    <textarea name="result" id="result" disabled style="min-height: 192px;"><?= isset($code)?$code:"" ?></textarea>
                    <div class="flex">
                        <button id="copy-result">Copiar</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="w-3">
                    <div class="table-lex-result">
                        <h3>Análise léxica</h3>
                        <?php
                            if(isset($lexResp)){
                                if(!empty($lexResp['errors'])){
                                    ?>
                                        <h3 style="color: red">Gerado com erro!</h3>
                                    <?php
                                }
                                if(!empty($lexResp['tokens'])){
                                    generateTokenTable($lexResp['tokens']);
                                }
                            }
                        ?>
                    </div>
                </div>
                <?php if(isset($lexResp) && empty($lexResp['errors'])){ ?>
                    <div class="w-3 second">
                        <h3>Análise Sintática</h3>
                        <?php 
                            if($analise){  
                                ?>
                                <table>
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Lexema</th>
                                        <th>Escopo</th>
                                        <th>Categoria</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        foreach ($SLR->getSymbolTable() as $item): ?>
                                            <tr>
                                            <td><?php echo $item['name']; ?></td>
                                            <td><?php echo $item['type']; ?></td>
                                            <td><?php echo $item['lexeme']; ?></td>
                                            <td><?php echo $item['scope']; ?></td>
                                            <td><?php echo $item['category']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php 
                            }else{
                                ?>
                                    <h3 style="color: red">Análise sintática com erros</h3>
                                <?php
                                foreach($SLR->getErrors() as $erro){
                                    echo "<strong>$erro</strong><br>";
                                }
                            }
                        ?>
                    </div>
                    <?php 
                        /* análise semantica*/ 
                        if($analise){ ?>
                        <div class="w-3">
                            <h3>Análise Semântica</h3>
                            <?php 
                                if($semantic->analyze()){ ?>
                                    <div id="tabelasintatica">
                                        <?php printTree($SLR->getDerivationTree()->getTree()); ?>
                                    </div>
                                <?php
                                }else{
                                    ?>
                                        <h3 style="color: red">Análise Semântica com erros</h3>
                                    <?php
                                    foreach($semantic->getErrors() as $erro){
                                        echo "<strong>$erro</strong><br>";
                                    }
                                }
                            ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>    
<?php include './frontend/componentes/footer.php'; ?>