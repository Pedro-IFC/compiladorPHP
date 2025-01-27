<?php 
class CodeGenerator{
    private static CodeParser $codeParser;
    private static $instance;

    private function __construct(){
        self::$codeParser = new CodeParserC();
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new CodeGenerator();
        }
        return self::$instance;
    } 

    public static function generate($tokenList){
        return self::$codeParser->generateCode($tokenList);
    } 
}