<?php

namespace WandersonBarradas\XmlValidator\Support;

use LibXMLError;

class ErrorFormatter
{
    /**
     * Formata um array de erros LibXMLError para um formato padrão.
     *
     * @param LibXMLError[] $errors
     * @return array<int, array{level: int, code: int, column: int, message: string, line: int, file: string|null}>
     */
    public static function format(array $errors): array
    {
        $formattedErrors = [];
        foreach ($errors as $error) {
            $formattedErrors[] = self::formatSingle($error);
        }
        return $formattedErrors;
    }

    /**
     * Formata um único erro LibXMLError.
     *
     * @return array{level: int, code: int, column: int, message: string, line: int, file: string|null}
     */
    public static function formatSingle(LibXMLError $error): array
    {
        // Aqui você pode adicionar lógica para traduzir
        // ou melhorar as mensagens com base no $error->code, se desejar.
        // Ex: $message = trans("xml-validation-errors.{$error->code}", [], trim($error->message));

        return [
            'level' => $error->level, // Ex: LIBXML_ERR_WARNING, LIBXML_ERR_ERROR, LIBXML_ERR_FATAL
            'code' => $error->code,   // Código numérico do erro libxml
            'column' => $error->column, // Coluna onde o erro ocorreu
            'message' => trim($error->message), // Mensagem original do libxml
            'line' => $error->line,     // Linha onde o erro ocorreu
            'file' => $error->file,     // Arquivo (geralmente vazio para strings)
        ];
    }
}
