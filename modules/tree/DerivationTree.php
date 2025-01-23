<?php 

class DerivationTree {
    private $stack = [];

    public function pushTerminal(Token $token): void {
        // Empilha o token como um nó terminal
        $this->stack[] = [
            'type' => 'terminal',
            'value' => $token->getLexeme()
        ];
    }

    public function reduce(string $nonTerminal, int $productionLength): void {
        // Retira os últimos `productionLength` nós da pilha
        $children = [];
        for ($i = 0; $i < $productionLength; $i++) {
            $children[] = array_pop($this->stack);
        }

        // Inverte os filhos para manter a ordem correta
        $children = array_reverse($children);

        // Cria um novo nó não terminal
        $this->stack[] = [
            'type' => 'nonTerminal',
            'value' => $nonTerminal,
            'children' => $children
        ];
    }

    public function getTree(): array {
        return $this->stack;
    }
}
