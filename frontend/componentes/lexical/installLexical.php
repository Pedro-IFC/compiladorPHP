<div class="text">
    <h3>Baixar projeto do github</h3>
    <div class="programminblock">
        git clone https://github.com/Pedro-IFC/analisadorLexicoPHP.git
    </div>
    <h3>Rodar o docker</h3>
    <div class="programminblock">
        cd /caminho/do/projeto <br>
        docker-compose up --build
    </div>
    <h3>Gerar nova tabela léxica</h3>
    <p>Para gerar uma nova tabela léxica é possível gerar através do programa gals e então salvar o html no diretório raiz como "tabelalexica.html" e então rodar o código:</p>
    <p>Um exemplo de tabela léxica em html é pode ser visto clicando <a href="/lexical/tabela">aqui</a></p>
    <div class="programminblock">
        cd /caminho/do/projeto <br>
        php generateLexicalTable.php
    </div>
    <p>Ou então criar dentro do diretório uma tabela léxica em json</p>
</div>