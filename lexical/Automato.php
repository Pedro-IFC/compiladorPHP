<?php
class Automato {
    public static function parseTokens($string, $Lexical, $serialize) : array {
        $state = 0;
        $lexeme = '';
        $tokens = [];
        $col = 1;
        $line = 1;
        $startCol = 1;
        $errorList=[];
        $totalChars = strlen($string);
        for ($index = 0; $index <= $totalChars; $index++) {
            $char = $string[$index] ?? " ";
            if ($char === "\n") {
                $line++;
                $col = 1;
            }
            if ($lexeme === '') {
                $startCol = $col;
            }
            if (isset($Lexical[$state][$char])) {
                $state = $Lexical[$state][$char];
                $lexeme .= $char;
            } else if (isset($Lexical[$state]['token'])) {
                $token = $Lexical[$state]['token'];
                if (!in_array($token, [":", "?"])) {
                    $newToken = new Token($token, $lexeme, $startCol, $line);
                    $tokens[] = $serialize ? serialize($newToken) : $newToken;
                }
                if ($token === ":" || strlen(trim($lexeme))) {
                    $index--;
                    $col--;
                } else if(!ctype_space($char)) {
                    $newToken = new Token("Não reconhecido", $char, $startCol, $line);
                    $tokens[] = $serialize ? serialize($newToken) : $newToken;
                    $errorList[] = "Não reconhecido ($char), col: $startCol, line: $line";
                    break;
                }
                $lexeme = '';
                $state = 0;
            } else {
                $errorList[] = "Não reconhecido ($char), col: $startCol, line: $line";
            }
            if ($index== $totalChars && !in_array($Lexical[$state]['token'], [":", "?"])) {
                $tokens[] = new Token($Lexical[$state]['token'], $lexeme, $startCol, $line);
            }
            $col++;
        }
        return [
            'tokens'=>$tokens,
            'errors' => $errorList
        ];
    }
}