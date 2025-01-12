<?php 
    $json_data = json_decode(file_get_contents("./data/lexical/tabelalexica.json"));
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><structure></structure>');
    $xml->addChild('type', 'fa');
    $automaton = $xml->addChild('automaton');
    $token_to_state = [];
    $state_id = 0;
    $total_states = count($json_data);
    $radius = 1800;
    foreach ($json_data as $token_entry) {
        $token = isset($token_entry->token) ? $token_entry->token : null;
        $state_name = "q" . $state_id;
        $token_to_state[$token] = $state_id;
        $state = $automaton->addChild('state');
        $state->addAttribute('id', (string) $state_id);
        $state->addAttribute('name', $state_name);
        $angle = (2 * M_PI * $state_id) / $total_states;
        $x_pos = 200 + $radius * cos($angle); 
        $y_pos = 200 + $radius * sin($angle); 
        $x = $state->addChild('x', $x_pos);
        $y = $state->addChild('y', $y_pos);
        if($state_id == 0){
            $state->addChild('initial');
        }
        if(!is_int($token) && $state_id != 0){
            $state->addChild('final');
        }
        $state_id++;
    }
    $state_id = 0;
    foreach ($json_data as $token_entry) {
        $arrayToken = (array) $token_entry;
        foreach($arrayToken as $key => $arToken){
            if(is_int($arToken)){
                $transition = $automaton->addChild('transition');
                $transition->addChild('from', (string) $state_id);
                $transition->addChild('to', (string) $arToken);
                @$transition->addChild('read', $key);
            }
        }
        $state_id++;
    }
    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->formatOutput = true;
    $dom->save('./data/lexical/automato.jff');
?>
