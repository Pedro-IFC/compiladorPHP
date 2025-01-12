<?php 

class SLRDoc {
    static public $grammar = [
        ["head" => "<programa>", "body" => ["INICIO", "<lista_de_declaracoes>", "FIM"]],
        ["head" => "<lista_de_declaracoes>", "body" => ["<declaracao>", "<lista_de_declaracoes>"]],
        ["head" => "<lista_de_declaracoes>", "body" => []],
        ["head" => "<declaracao>", "body" => ["<declaracao_variavel>"]],
        ["head" => "<declaracao>", "body" => ["<declaracao_funcao>"]],
        ["head" => "<declaracao_variavel>", "body" => ["<tipo>", "IDENTIFICADORES", "PONTOVIRGULA"]],
        ["head" => "<tipo>", "body" => ["INT"]],
        ["head" => "<tipo>", "body" => ["CHAR"]],
        ["head" => "<tipo>", "body" => ["FLOAT"]],
        ["head" => "<tipo>", "body" => ["ARRAY", "ABRECONCHETES", "CONSTINTEIRAS", "FECHACONCHETES", "DE", "<tipo>"]],
        ["head" => "<declaracao_funcao>", "body" => ["FUNCTION", "IDENTIFICADORES", "ABREPAREN", "<parametros>", "FECHAPAREN", "ABRECHAVES", "<lista_de_comandos>", "FECHACHAVES"]],
        ["head" => "<parametros>", "body" => ["<parametro>", "VIRGULA", "<parametros>"]],
        ["head" => "<parametros>", "body" => ["<parametro>"]],
        ["head" => "<parametros>", "body" => []],
        ["head" => "<parametro>", "body" => ["<tipo>", "IDENTIFICADORES"]],
        ["head" => "<lista_de_comandos>", "body" => ["<comando>", "<lista_de_comandos>"]],
        ["head" => "<lista_de_comandos>", "body" => []],
        ["head" => "<comando>", "body" => ["<comando_atribuicao>"]],
        ["head" => "<comando>", "body" => ["<comando_leitura>"]],
        ["head" => "<comando>", "body" => ["<comando_impressao>"]],
        ["head" => "<comando>", "body" => ["<comando_retorno>"]],
        ["head" => "<comando>", "body" => ["<comando_selecao>"]],
        ["head" => "<comando>", "body" => ["<comando_repeticao>"]],
        ["head" => "<comando>", "body" => ["<chamada_funcao>", "PONTOVIRGULA"]],
        ["head" => "<comando_atribuicao>", "body" => ["IDENTIFICADORES", "IGUAL", "<expressao>", "PONTOVIRGULA"]],
        ["head" => "<comando_leitura>", "body" => ["READ", "ABREPAREN", "IDENTIFICADORES", "FECHAPAREN", "PONTOVIRGULA"]],
        ["head" => "<comando_impressao>", "body" => ["WRITE", "ABREPAREN", "<expressao>", "FECHAPAREN", "PONTOVIRGULA"]],
        ["head" => "<comando_retorno>", "body" => ["RETURN", "<expressao>", "PONTOVIRGULA"]],
        ["head" => "<comando_selecao>", "body" => ["IF", "ABREPAREN", "<expressao_logica>", "FECHAPAREN", "ABRECHAVES", "<lista_de_comandos>", "FECHACHAVES", "<comando_senao>"]],
        ["head" => "<comando_senao>", "body" => ["ELSE", "ABRECHAVES", "<lista_de_comandos>", "FECHACHAVES"]],
        ["head" => "<comando_senao>", "body" => []],
        ["head" => "<comando_repeticao>", "body" => ["WHILE", "ABREPAREN", "<expressao_logica>", "FECHAPAREN", "ABRECHAVES", "<lista_de_comandos>", "FECHACHAVES"]],
        ["head" => "<comando_repeticao>", "body" => ["DO", "ABRECHAVES", "<lista_de_comandos>", "FECHACHAVES", "WHILE", "ABREPAREN", "<expressao_logica>", "FECHAPAREN", "PONTOVIRGULA"]],
        ["head" => "<chamada_funcao>", "body" => ["IDENTIFICADORES", "ABREPAREN", "<lista_de_argumentos>", "FECHAPAREN"]],
        ["head" => "<lista_de_argumentos>", "body" => ["<expressao>", "VIRGULA", "<lista_de_argumentos>"]],
        ["head" => "<lista_de_argumentos>", "body" => ["<expressao>"]],
        ["head" => "<lista_de_argumentos>", "body" => []],
        ["head" => "<expressao>", "body" => ["<expressao>", "MAIS", "<termo>"]],
        ["head" => "<expressao>", "body" => ["<expressao>", "MENOS", "<termo>"]],
        ["head" => "<expressao>", "body" => ["<termo>"]],
        ["head" => "<termo>", "body" => ["<termo>", "ASTERISTICO", "<fator>"]],
        ["head" => "<termo>", "body" => ["<termo>", "DIVISAO", "<fator>"]],
        ["head" => "<termo>", "body" => ["<termo>", "MODULODADIVISAO", "<fator>"]],
        ["head" => "<termo>", "body" => ["<fator>"]],
        ["head" => "<fator>", "body" => ["ABREPAREN", "<expressao>", "FECHAPAREN"]],
        ["head" => "<fator>", "body" => ["IDENTIFICADORES"]],
        ["head" => "<fator>", "body" => ["CONSTINTEIRAS"]],
        ["head" => "<fator>", "body" => ["CONSTREAIS"]],
        ["head" => "<fator>", "body" => ["CONSTCHAR"]],
        ["head" => "<expressao_logica>", "body" => ["<expressao>", "OPERADORRELACIONAL", "<expressao>"]],
        ["head" => "<expressao_logica>", "body" => ["<expressao>", "OPERADORLOGICO", "<expressao_logica>"]],
        ["head" => "<expressao_logica>", "body" => ["NAO", "<expressao_logica>"]],
        ["head" => "<expressao_logica>", "body" => ["ABREPAREN", "<expressao_logica>", "FECHAPAREN"]],
        ["head" => "<expressao_logica>", "body" => ["TRUE"]],
        ["head" => "<expressao_logica>", "body" => ["FALSE"]],
        ["head" => "<expressao>", "body" => ["MENOS", "<expressao>"]]
    ];
    static public $parsingTable = [
        0 => [
            "action" => [
                "INICIO" => ["type" => "shift", "state" => 1],
            ],
            "goto" => [
                "<programa>" => 2,
            ],
        ],
        1 => [
            "action" => [
                "FUNCTION" => ["type" => "shift", "state" => 15],
                "INT" => ["type" => "shift", "state" => 3],
                "CHAR" => ["type" => "shift", "state" => 4],
                "FLOAT" => ["type" => "shift", "state" => 5],
                "ARRAY" => ["type" => "shift", "state" => 6],
            ],
            "goto" => [
                "<tipo>" => 7,
                "<declaracao>" => 8,
                "<lista_de_declaracoes>" => 9,
            ],
        ],
        2 => [
            "action" => [
                "$" => ["type" => "accept"],
            ],
            "goto" => [],
        ],
        3 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 10],
            ],
            "goto" => [],
        ],
        4 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 11],
            ],
            "goto" => [],
        ],
        5 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 12],
            ],
            "goto" => [],
        ],
        6 => [
            "action" => [
                "ABRECONCHETES" => ["type" => "shift", "state" => 13],
            ],
            "goto" => [],
        ],
        7 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 14],
            ],
            "goto" => [],
        ],
        8 => [
            "action" => [
                "INT" => ["type" => "shift", "state" => 3],
                "CHAR" => ["type" => "shift", "state" => 4],
                "FLOAT" => ["type" => "shift", "state" => 5],
                "ARRAY" => ["type" => "shift", "state" => 6],
            ],
            "goto" => [
                "<declaracao>" => 8,
                "<lista_de_declaracoes>" => 9,
            ],
        ],
        9 => [
            "action" => [
                "FIM" => ["type" => "reduce", "production" => "<programa> → INICIO <lista_de_declaracoes> FIM"],
            ],
            "goto" => [],
        ],
        10 => [
            "action" => [
                "PONTOVIRGULA" => ["type" => "reduce", "production" => "<declaracao_variavel> → <tipo> IDENTIFICADORES PONTOVIRGULA"],
            ],
            "goto" => [],
        ],
        11 => [
            "action" => [
                "PONTOVIRGULA" => ["type" => "reduce", "production" => "<declaracao_variavel> → <tipo> IDENTIFICADORES PONTOVIRGULA"],
            ],
            "goto" => [],
        ],
        12 => [
            "action" => [
                "PONTOVIRGULA" => ["type" => "reduce", "production" => "<declaracao_variavel> → <tipo> IDENTIFICADORES PONTOVIRGULA"],
            ],
            "goto" => [],
        ],
        13 => [
            "action" => [
                "CONSTINTEIRAS" => ["type" => "shift", "state" => 16],
            ],
            "goto" => [],
        ],
        14 => [
            "action" => [
                "PONTOVIRGULA" => ["type" => "reduce", "production" => "<declaracao_funcao> → FUNCTION IDENTIFICADORES ABREPAREN <parametros> FECHAPAREN ABRECHAVES <lista_de_comandos> FECHACHAVES"],
            ],
            "goto" => [],
        ],
        15 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 17],
            ],
            "goto" => [],
        ],
        16 => [
            "action" => [
                "DE" => ["type" => "shift", "state" => 18],
            ],
            "goto" => [],
        ],
        17 => [
            "action" => [
                "ABREPAREN" => ["type" => "shift", "state" => 19],
            ],
            "goto" => [],
        ],
        18 => [
            "action" => [
                "<tipo>" => ["type" => "shift", "state" => 7],
            ],
            "goto" => [],
        ],
        19 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 20],
                "FECHAPAREN" => ["type" => "shift", "state" => 23],
            ],
            "goto" => [],
        ],
        20 => [
            "action" => [
                "VIRGULA" => ["type" => "shift", "state" => 21],
                "FECHAPAREN" => ["type" => "reduce", "production" => "<parametros> → <parametro>"],
            ],
            "goto" => [],
        ],
        21 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 22],
            ],
            "goto" => [],
        ],
        22 => [
            "action" => [
                "FECHAPAREN" => ["type" => "reduce", "production" => "<parametros> → <parametro>"],
            ],
            "goto" => [],
        ],
        23 => [
            "action" => [
                "ABREPAREN" => ["type" => "shift", "state" => 24],
            ],
            "goto" => [],
        ],
        24 => [
            "action" => [
                "IDENTIFICADORES" => ["type" => "shift", "state" => 25],
            ],
            "goto" => [],
        ],
        25 => [
            "action" => [
                "VIRGULA" => ["type" => "shift", "state" => 26],
                "FECHAPAREN" => ["type" => "reduce", "production" => "<expressao_logica> → <expressao> OPERADORRELACIONAL <expressao>"],
            ],
            "goto" => [],
        ],
        // Continuar com outros estados conforme necessário
    ];
    
}
