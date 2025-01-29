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
function generateSymbolTable($table){
    ?>
        <table>
        <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Lexema</th>
            <th>Escopo</th>
            <th>Categoria</th>
            <th>Linha</th>
        </tr>
        </thead>
        <tbody>
        <?php 
            foreach ($table as $item): ?>
                <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['type']; ?></td>
                <td><?php echo $item['lexeme']; ?></td>
                <td><?php echo $item['scope']; ?></td>
                <td><?php echo $item['category']; ?></td>
                <td><?php echo $item['line']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}