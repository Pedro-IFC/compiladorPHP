<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/modules/lexical/Lexical.php';
require __DIR__ . '/modules/sintatic/AnalisadorSintaticoPreditivo.php';
require __DIR__ . '/modules/sintatic/AnalisadorSintaticoDescidaRecursiva.php';
require __DIR__ . '/modules/sintatic/AnalisadorLRSimples.php';
require __DIR__ . '/data/sintatic/SLRDoc.php';

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

// Analizador Sintático
$app->get('/sintatic/home', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Léxico";
    include './frontend/sintatic.php';
    return $response;
});
$app->get('/sintatic/asp', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/componentes/header.php';
    include './frontend/componentes/sintatic/asp/asp.php';
    include './frontend/componentes/footer.php';
    return $response;
});
$app->get('/sintatic/sdr', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/componentes/header.php';
    include './frontend/componentes/sintatic/sdr/sdr.php';
    include './frontend/componentes/footer.php';
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