<?php

namespace WandersonBarradas\XmlValidator\Exceptions;

use LibXMLError;

class XmlParseException extends \Exception
{
    protected array $libXmlErrors = [];

    /**
     * @param LibXMLError[] $libXmlErrors
     */
    public function __construct(array $libXmlErrors, string $message = "Erro ao analisar (parse) o XML.", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->libXmlErrors = $libXmlErrors;
    }

    /**
     * Retorna os erros originais do libxml.
     *
     * @return LibXMLError[]
     */
    public function getLibXmlErrors(): array
    {
        return $this->libXmlErrors;
    }

    /**
     * Retorna os erros formatados (exemplo).
     * Adapte conforme necessário, talvez usando um ErrorFormatter.
     *
     * @return array<int, array{level: int, code: int, column: int, message: string, line: int, file: string|null}>
     */
    public function getFormattedErrors(): array
    {
        // Poderia usar uma classe ErrorFormatter aqui
        $formatted = [];
        foreach ($this->libXmlErrors as $error) {
            $formatted[] = [
                'level' => $error->level,
                'code' => $error->code,
                'column' => $error->column,
                'message' => trim($error->message),
                'line' => $error->line,
                'file' => $error->file, // Pode ser útil
            ];
        }
        return $formatted;
        // return ErrorFormatter::format($this->libXmlErrors); // Exemplo com formatter
    }
}
