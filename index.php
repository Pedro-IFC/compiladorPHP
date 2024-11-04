<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/lexical/Lexical.php';
require __DIR__ . '/sintaticoDescentRecursive/sintaticoDescenteRecursive.php';
require __DIR__ . '/sintaticPredictive/AnalisadorSintaticoPreditivo.php';

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
$app->post('/lexical/validate/', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Léxico";
    $parsedBody = $request->getParsedBody();
    if (isset($parsedBody['string'])) {
        $lexicalC = new Lexical($parsedBody['string']);
        $result = json_encode($lexicalC->validate(true));
        $response->getBody()->write($result);
    } else {
        $response->getBody()->write(json_encode(["error" => "Parâmetro 'string' não fornecido."]));
        return $response->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }
    return $response->withHeader('Content-Type', 'application/json');
});
// Analizador Sintático Descida recursiva
$app->get('/sintaticRecursiveDescentSyntax/home', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/sintaticRecursiveDescentSyntax.php';
    return $response;
});
$app->get('/sintaticRecursiveDescentSyntax/gramatica', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/sintaticRDSDescricao.php';
    return $response;
});
$app->post('/sintaticRecursiveDescentSyntax/validate/', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sinstático";
    $parsedBody = $request->getParsedBody();
    if (isset($parsedBody['string'])) {
        $lexicalC = new Lexical($parsedBody['string']);
        $AnalisadorDR = New AnalisadorSintaticoDescenteRecursive($lexicalC);
        $result = json_encode([
            "result" => $AnalisadorDR->analisar(),
            "tree" => $AnalisadorDR->getArvore()
        ]);
        $response->getBody()->write($result);
    } else {
        $response->getBody()->write(json_encode(["error" => "Parâmetro 'string' não fornecido."]));
        return $response->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }
    return $response->withHeader('Content-Type', 'application/json');
});


// Analizador Sintático Preditivo
$app->get('/sintaticPredictive/home', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/sintaticPredictive.php';
    return $response;
});
$app->get('/sintaticPredictive/gramatica', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sintático";
    include './frontend/sintaticPredictiveGramatica.php';
    return $response;
});
$app->post('/sintaticPredictive/validate/', function (Request $request, Response $response, $args) {
    $_SERVER['title'] = "Analisador Sinstático";
    $parsedBody = $request->getParsedBody();
    if (isset($parsedBody['string'])) {
        $lexicalC = new Lexical($parsedBody['string']);
        $AnalisadorDR = New AnalisadorSintaticoDescenteRecursive($lexicalC);
        $result = json_encode([
            "result" => $AnalisadorDR->analisar(),
            "tree" => $AnalisadorDR->getArvore()
        ]);
        $response->getBody()->write($result);
    } else {
        $response->getBody()->write(json_encode(["error" => "Parâmetro 'string' não fornecido."]));
        return $response->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();