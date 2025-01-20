<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require './vendor/autoload.php';
require './modules/lexical/Lexical.php';
require './modules/sintatic/AnalisadorRSL.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Trabalho de Compiladores";
    include './frontend/home.php';
    
    return $response;
});

// Analizador Léxico
$app->get('/lexical/home', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Léxico";
    include './frontend/lexical.php';
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
    include './frontend/automatoLexico.php';
    return $response;
});

$app->get('/sintatic/slr', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/componentes/header.php';
    include './frontend/componentes/sintatic/slr/slr.php';
    include './frontend/componentes/footer.php';
    return $response;
});
$app->run();