<?php 
    $dom = new DOMDocument();
    @$dom->loadHTMLFile('./data/lexical/tabelalexica.html');
    $tabelas = $dom->getElementsByTagName('table');
    if ($tabelas->length == 0) {
        throw new Exception("Nenhuma tabela encontrada no arquivo HTML.");
    }
    $tabela = $tabelas->item(0);
    $linhas = $tabela->getElementsByTagName('tr');
    $jsonArray = [];
    $cabecalho = [];
    $colunasEntrada = $linhas->item(1)->getElementsByTagName('td');
    foreach ($colunasEntrada as $coluna) {
        $v = trim($coluna->nodeValue);
        $v=$v=="' '"?" ":$v;
        $cabecalho[] = $v;
    }
    for ($i = 2; $i < $linhas->length; $i++) {
        $linha = $linhas->item($i);
        $celulas = $linha->getElementsByTagName('td');
        $estadoAtual = trim($celulas->item(0)->nodeValue);
        $token = trim($celulas->item(1)->nodeValue);
        $estadoArray = [
            'token' => $token !== '' ? $token : '?'
        ];
        for ($j = 2; $j < $celulas->length; $j++) {
            $valor = trim($celulas->item($j)->nodeValue);
            $valor=$valor=="' '"?" ":$valor;
            if ($valor !== '-') {
                $estadoArray[$cabecalho[$j - 2]] = (int)$valor;
            }
        }
        $jsonArray[] = $estadoArray;
    }
    $file = fopen("./data/lexical/tabelalexica.json", "w");
    fwrite($file, json_encode($jsonArray));
    fclose($file);
?>
