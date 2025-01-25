<?php 
function printTree(array $stack, int $level = 0): void {
    foreach ($stack as $node) { // Indentação com 4 espaços por nível
        if ($node['type'] === 'nonTerminal') {
            ?>
                <div class='non-terminal'><?= $node['value'] ?></div>
            <?php 
            if (isset($node['children'])) {
                printTree($node['children'], $level + 1); // Recursão para filhos
            }
        } elseif ($node['type'] === 'terminal') {
            ?>
                <div class='terminal'><?= $node['value']->getName().'("'.$node['value']->getLexeme().'")' ?></div>
            <?php 
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