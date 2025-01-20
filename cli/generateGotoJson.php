<?php
// Carrega o arquivo HTML
$dom = new DOMDocument();
@$dom->loadHTMLFile('data/sintatic/tabelaslr.html');

// Obtém todas as tabelas
$tabelas = $dom->getElementsByTagName('table');
if ($tabelas->length == 0) {
    throw new Exception("Nenhuma tabela encontrada no arquivo HTML.");
}

// Extrai cabeçalhos e dados das tabelas
$jsonArray = [];
$tabela = $tabelas[0]; // Assume que a primeira tabela é a relevante
$linhas = $tabela->getElementsByTagName('tr');

// Obtém os cabeçalhos (primeiras linhas)
$colunasHeader = [];
foreach ($linhas as $index => $linha) {
    $colunas = $linha->getElementsByTagName('td');
    if ($index == 0) { // Pulando as linhas de títulos
        continue;
    }

    if ($index == 1) {
        foreach ($colunas as $col) {
            $colunasHeader[] = trim($col->textContent);
        }
        continue;
    } 

    $estado = null;
    $dadosLinha = [];
    foreach ($colunas as $colIndex => $col) {
        if ($colIndex == 0) { // Primeira coluna é o estado
            $estado = trim($col->textContent);
        } else {
            $token = $colunasHeader[$colIndex-1];
            $valor = trim($col->textContent);
            if ($valor !== '-' && $valor !== '') {
                $dadosLinha[$token] = $valor;
            }
        }
    }
    if ($estado !== null && !empty($dadosLinha)) {
        $jsonArray[$estado] = $dadosLinha;
    }
}

$data = $jsonArray;
$actionTable=[];
$gotoTable=[];
foreach ($data as $state => $transitions) {
    $actionTable[$state] = [];
    foreach ($transitions as $symbol => $action) {
        if (str_starts_with($action, 'SHIFT')) {
            $nextState = (int) filter_var($action, FILTER_SANITIZE_NUMBER_INT);
            $actionTable[$state][$symbol] = ['type' => 'SHIFT', 'state' => $nextState];
        } elseif (str_starts_with($action, 'REDUCE')) {
            $ruleIndex = (int) filter_var($action, FILTER_SANITIZE_NUMBER_INT);
            $actionTable[$state][$symbol] = ['type' => 'REDUCE', 'rule' => $ruleIndex];
        } elseif ($action === 'ACCEPT') {
            $actionTable[$state][$symbol] = ['type' => 'ACCEPT'];
        } else {
            $gotoTable[$state][$symbol] = (int) $action;
        }
    }
}
$file = fopen("./data/sintatic/tabelagoto.json", "w");
fwrite($file, json_encode(["goto"=>$gotoTable, "actionTable" => $actionTable], JSON_PRETTY_PRINT));
fclose($file);

echo "JSON gerado com sucesso!";
?>
