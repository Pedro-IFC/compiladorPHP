<div class="form">
    <form method="GET">
        <textarea name="string" id="string_txt"><?php if(isset($_GET['string'])){echo $_GET['string']; }else{ ?>programa a(){
se(a>=2){
escreva(a);
}
}<?php } ?></textarea>
        <div class="flex">
            <div class="selection">
                Derivarar a partir de<br>
                <select name="inicio" id="inicio">
                    <option value="programa">Programa</option>
                    <!--<option value="lista_comandos">Lista de comandos</option>-->
                </select>
            </div>
            <button type="submit">Gerar Análise Sitática</button>
            <a href="/sintaticPredictive/home" class="red">
                <svg width="18px" height="18px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="white" d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>  
            </a>
        </div>
    </form>
    <div class="table-lex-result">
        <?php
            if(isset($_GET['string'])){
                $lex = new Lexical($_GET['string']);
                $AnalisadorP = New AnalisadorSintaticoPreditivo($lex->validate(false)['resp']['tokens']);
                $return = $AnalisadorP->validarPrograma($_GET['string']);
                if($return[0]){
                    ?>
                        <h3 style="color: green">Passou da análise sintatica!</h3>
                    <?php
                }else{
                    ?>
                    <h3 style="color: red">Não passou na análise sintática!</h3>
                    <table class="tokens">
                        <tr class="token">
                            <td class="name reconhecido"><?= $return[1] ?></td>
                        </tr>
                    </table>
                    <?php
                }
            }
        ?>
    </div>
</div>