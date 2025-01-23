<?php 
function printTree(array $stack, int $level = 0): void {
    foreach ($stack as $node) {
        $indent = str_repeat("-", $level); // Indentação com 4 espaços por nível
        if ($node['type'] === 'nonTerminal') {
            echo "<div class='non-terminal'>{$indent}{$node['value']}</div>";
            if (isset($node['children'])) {
                printTree($node['children'], $level + 1); // Recursão para filhos
            }
        } elseif ($node['type'] === 'terminal') {
            echo "<div class='terminal'>{$indent}{$node['value']}</div>";
        }
    }
}

function generateTokenTable($tokens){
    ?>
    <table class="tokens">
    <?php 
        foreach($tokens as $token){
            ?>
            <tr class="token">
                <td class="name <?= $token->getName() ?>"><?= $token->getName() ?></td>
                <td class="lexeme"><?= $token->getLexeme() ?></td>
                <td class="pos">L: <?= $token->getLine() ?><br>C:<?= $token->getInicio() ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php 
}