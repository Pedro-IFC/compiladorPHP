<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SERVER['title'] ?></title>
    <link rel="stylesheet" href="/frontend/style.css">
</head>
<body>
    <header class="main-header">
        <div class="al-container">
            <ul>
                <li><a href="/"><strong>Home</strong></a></li>
                <li>
                    <strong>Lexical</strong>
                    <ul class="submenu">
                        <li><a href="/lexical/home">Analizador Léxico</a></li>
                        <li><a href="/lexical/tabela">Tabela Léxica</a></li>
                        <li><a href="/lexical/automato">Ver Automato</a></li>
                        <li><a download href="/data/lexical/automato.jff">Baixar Automato</a></li>
                    </ul>
                </li>
                <li>
                    <strong>Sintatico De Descida Recursiva</strong>
                    <ul class="submenu">
                        <li><a href="/sintaticRecursiveDescentSyntax/home">Analizador</a></li>
                        <li><a href="/sintaticRecursiveDescentSyntax/gramatica">Gramática</a></li>
                    </ul>
                </li>
                <li>
                    <strong>Sintático Preditivo</strong>
                    <ul class="submenu">
                        <li><a href="/sintaticPredictive/home">Analizador</a></li>
                        <li><a href="/sintaticPredictive/gramatica">Gramática</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </header>
    <div id="wrapper">
    