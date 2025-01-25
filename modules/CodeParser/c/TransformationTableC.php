<?php 

class TransformationTableC{
    private static array $table = [
        "MAIN" => "main",
        "AP" => "(",
        "FP" => ")",
        "AC" => "{",
        "FC" => "}",
        "IMPRIMA" => "printf",
        "AC" => "{",
        "AC" => "{",
        "INT" => "int",
        "RETORNO" => "return",
        "MUL" => "*",
        "EQ" => "=",
        "SE" => "IF",
        "SENAO" => "else",

    ];
    public static function getTable(): array{
        return self::$table;
    }
}