<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require './vendor/autoload.php';
require './modules/functions.php';
require './modules/tokens/Token.php';
require './modules/lexical/Lexical.php';
require './modules/lexical/AnalisadorLexico.php';
require './modules/sintatic/AnalisadorSRL.php';
require './modules/tree/Node.php';
require './modules/tree/DerivationTree.php';
require './modules/tables/SymbolTable.php';
require './modules/semantic/SemanticAnalyzer.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Trabalho de Compiladores";
    include './frontend/home.php';
    
    return $response;
});

// Analizador Léxico
$app->get('/lexical/home', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Léxico";
    include './frontend/componentes/lexical/index.php';
    return $response;
});
$app->get('/lexical/tabela', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Léxico";
    include './frontend/componentes/header.php';
    include './data/lexical/tabelalexica.html';
    include './frontend/componentes/footer.php';
    return $response;
});
$app->get('/lexical/automato', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Léxico";
    include './frontend/componentes/lexical/automato.php';
    return $response;
});

// Analizador Sintático
$app->get('/sintatic/home', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/componentes/sintatic/index.php';
    return $response;
});
$app->get('/sintatic/tabela', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/componentes/header.php';
    include './data/sintatic/tabelaslr.html';
    include './frontend/componentes/footer.php';
    return $response;
});
$app->get('/sintatic/grammar', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/componentes/header.php';
    include './frontend/componentes/sintatic/grammar.html';
    include './frontend/componentes/footer.php';
    return $response;
});


$app->run();