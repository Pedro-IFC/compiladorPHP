<?php
// Carrega o arquivo HTML
$dom = new DOMDocument();
@$dom->loadHTMLFile('./data/sintatic/tabelaslr.html');

// Obtém todas as tabelas
$tabelas = $dom->getElementsByTagName('table');
if ($tabelas->length == 0) {
    throw new Exception("Nenhuma tabela encontrada no arquivo HTML.");
}

// Extrai cabeçalhos e dados das tabelas
$jsonArray = [
    "acao" => [],
    "desvio" => []
];

// Processa a tabela
foreach ($tabelas as $tabela) {
    $linhas = $tabela->getElementsByTagName('tr');
    $headersAcao = [];
    $headersDesvio = [];
    $isHeader = true;

    foreach ($linhas as $linha) {
        $celulas = $linha->getElementsByTagName('td');
        $dadosLinha = [];
        $estado = null;

        foreach ($celulas as $index => $celula) {
            $conteudo = trim($celula->textContent);

            if ($isHeader) {
                // Processa cabeçalhos
                if ($index == 0) {
                    $headersAcao[] = "ESTADO";
                } elseif ($index < 41) {
                    $headersAcao[] = $conteudo;
                } else {
                    $headersDesvio[] = $conteudo;
                }
            } else {
                // Processa dados
                if ($index == 0) {
                    $estado = $conteudo;
                } elseif ($index < 41) {
                    $dadosLinha["acao"][$headersAcao[$index]] = $conteudo;
                } else {
                    $dadosLinha["desvio"][$headersDesvio[$index - 41]] = $conteudo;
                }
            }
        }

        if (!$isHeader && $estado !== null) {
            $jsonArray["acao"][$estado] = $dadosLinha["acao"];
            $jsonArray["desvio"][$estado] = $dadosLinha["desvio"];
        }

        $isHeader = false;
    }
}

// Salva o JSON em um arquivo
$file = fopen("./data/sintatic/tabelagoto.json", "w");
fwrite($file, json_encode($jsonArray, JSON_PRETTY_PRINT));
fclose($file);

echo "JSON gerado com sucesso!";
?>
