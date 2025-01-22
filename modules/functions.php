<?php 
function printNode($node, $indent = 0) {
    $indentClass = "indent-" . $indent;
    if (is_object($node)) {
        if ($node instanceof Token) {
            echo '<div class="node token ' . $indentClass . '">';
            echo '<span class="token-name">Token:</span> ' . $node->getName() . 
                 ' <span class="token-lexeme">(Lexema: "' . $node->getLexeme() . '")</span>';
            echo '</div>';
        } else {
            echo '<div class="node non-terminal ' . $indentClass . '">';
            echo '<span class="node-symbol">Nó:</span> ' . $node->symbol;
            echo '</div>';
            if (!empty($node->children) && is_array($node->children)) {
                foreach ($node->children as $child) {
                    printNode($child, $indent + 1); 
                }
            }
        }
    } elseif (is_string($node)) {
        echo '<div class="node literal ' . $indentClass . '">';
        echo '<span class="token-literal">Token Literal:</span> ' . $node;
        echo '</div>';
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