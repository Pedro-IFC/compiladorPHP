<?php
class SymbolTable {
    
    private array $symbols = [];

    public function addSymbol(array $symbol): void {
        $this->symbols[$symbol['name']] = $symbol;
    }

    public function updateSymbol(string $name, array $attributes): void {
        if (isset($this->symbols[$name])) {
            $this->symbols[$name] = array_merge($this->symbols[$name], $attributes);
        }
    }

    public function getSymbol(string $name): ?array {
        return $this->symbols[$name] ?? null;
    }

    public function getAllSymbols(): array {
        return $this->symbols;
    }
}
