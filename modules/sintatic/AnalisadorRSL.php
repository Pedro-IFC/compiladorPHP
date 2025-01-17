<?php

class AnalisadorRSL {
    private array $tabelaGoto;
    private array $tabelaAcao;

    public function __construct(string $jsonFilePath) {
        $jsonData = file_get_contents($jsonFilePath);
        $parsedData = json_decode($jsonData, true);

        $this->tabelaAcao = $parsedData['acao'] ?? [];
        $this->tabelaGoto = $parsedData['desvio'] ?? [];
    }

    public function analisar(array $tokens): bool {
        $pilha = [0]; // Pilha de estados
        $indice = 0;  // Índice atual na lista de tokens

        while (true) {
            $estadoAtual = end($pilha); // Último estado da pilha
            $tokenAtual = $tokens[$indice] ?? null;

            $simbolo = $tokenAtual ? $tokenAtual->getName() : '$'; // Nome do token atual ou $
            var_dump("Estado " . $estadoAtual);
            var_dump("Token " . $tokenAtual->getName());
            var_dump($this->tabelaAcao[$estadoAtual][$simbolo]);
            $acao = $this->tabelaAcao[$estadoAtual][$simbolo] ?? '-';

            if ($acao === '-') {
                throw new Exception("Erro de análise no estado $estadoAtual com o símbolo '$simbolo'.");
            }

            if (strpos($acao, 'SHIFT') === 0) {
                // Transição SHIFT
                $novoEstado = (int) filter_var($acao, FILTER_SANITIZE_NUMBER_INT);
                array_push($pilha, $novoEstado);
                $indice++; // Avança para o próximo token
            } elseif (strpos($acao, 'REDUCE') === 0) {
                // Transição REDUCE
                $producao = (int) filter_var($acao, FILTER_SANITIZE_NUMBER_INT);
                // Aqui você pode ajustar a lógica para reduzir com base em uma gramática específica
                // Por exemplo, você poderia usar um array que define o tamanho de cada produção
                $tamanhoReducao = 1; // Supondo que REDUCE(X) remove X elementos (exemplo)
                for ($i = 0; $i < $tamanhoReducao; $i++) {
                    array_pop($pilha); // Remove estados da pilha
                }
                $estadoAnterior = end($pilha);
                $simboloGoto = "<simbolo_produzido>"; // Ajuste para o símbolo produzido
                $novoEstado = $this->tabelaGoto[$estadoAnterior][$simboloGoto] ?? null;

                if ($novoEstado === null || $novoEstado === '-') {
                    throw new Exception("Erro de GOTO no estado $estadoAnterior para o símbolo '$simboloGoto'.");
                }

                array_push($pilha, $novoEstado);
            } elseif ($acao === 'ACCEPT') {
                return true; // Aceitação bem-sucedida
            } else {
                throw new Exception("Ação inválida encontrada: $acao.");
            }
        }
    }
}
