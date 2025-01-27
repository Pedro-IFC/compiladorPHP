<?php 

class TransformationTableC{
    private static array $table = [
        'main' => 'int main',
        'ap' => '(',
        'fp' => ')',
        'ac' => ' {',
        'fc' => '}',
        'pv' => ';',
        'vir' => ', ',
        'eq' => ' = ',
        'mai' => ' > ',
        'men' => ' < ',
        'and' => ' && ',
        'or' => ' || ',
        'not' => '!',
        'dif' => ' != ',
        'deq' => ' == ',
        'menori' => ' <= ',
        'maiori' => ' >= ',
        'mul' => ' * ',
        'div' => ' / ',
        'mod' => ' % ',
        'imprima' => 'printf',
        'leia' => 'scanf',
        'se' => 'if',
        'senao' => 'else',
        'enquanto' => 'while',
        'para' => 'for',
        'retorno' => 'return'
    ];
    public static function getTable(): array{
        return self::$table;
    }
}