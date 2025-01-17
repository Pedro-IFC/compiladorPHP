#!/usr/bin/env php
<?php

require '/vendor/autoload.php';

$commands = [
    'generateLexicalTable' => './cli/generateLexicalTable.php',
    'generateLexicalAutomato' => './cli/generateLexicalAutomato.php',
];

$command = $argv[1] ?? null;

if (!$command || !isset($commands[$command])) {
    echo "Comando não reconhecido. Use:\n";
    foreach (array_keys($commands) as $cmd) {
        echo "  php compilador $cmd\n";
    }
    exit(1);
}

require '/' . $commands[$command];
