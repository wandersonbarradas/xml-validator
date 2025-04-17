<?php

namespace WandersonBarradas\XmlValidator;

class ValidationResult
{
    protected bool $isValid;
    protected array $errors;

    public function __construct(bool $isValid, array $errors = [])
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }

    /**
     * Verifica se a validação foi bem-sucedida.
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Verifica se existem erros de validação.
     */
    public function hasErrors(): bool
    {
        return !$this->isValid;
    }

    /**
     * Retorna os erros de validação formatados.
     *
     * @return array<int, array{level: int, code: int, column: int, message: string, line: int, file: string|null}>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
