<?php

namespace WandersonBarradas\XmlValidator;

use DOMDocument;
use WandersonBarradas\XmlValidator\Exceptions\XmlParseException;
use WandersonBarradas\XmlValidator\ValidationResult;

class Validator
{
    /**
     * Caminho relativo para o diretório de schemas dentro do pacote.
     */
    private const SCHEMA_DIR_PATH = __DIR__ . '/../schemas/PL_010_V1/';

    /**
     * Nome do arquivo de schema XSD a ser utilizado.
     */
    private const SCHEMA_FILE_NAME = 'procNFe_v4.00.xsd';

    /**
     * Valida o conteúdo de uma string XML contra o schema XSD definido na classe.
     *
     * @param string $xmlContent O conteúdo XML a ser validado.
     * @return ValidationResult O resultado da validação.
     *
     * @throws XmlParseException Se o XML for mal formatado e não puder ser carregado.
     * @throws \RuntimeException Se o arquivo de schema interno do pacote não for encontrado ou legível.
     */
    public function validate(string $xmlContent): ValidationResult
    {
        $schemaPath = self::SCHEMA_DIR_PATH . self::SCHEMA_FILE_NAME;

        if (!file_exists($schemaPath) || !is_readable($schemaPath)) {
            $errorMessage = "Arquivo de schema interno não encontrado ou ilegível: {$schemaPath}";
            throw new \RuntimeException($errorMessage);
        }

        $internalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;

        if (!@$dom->loadXML($xmlContent)) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            libxml_use_internal_errors($internalErrors);
            throw new XmlParseException($errors);
        }

        $isValid = @$dom->schemaValidate($schemaPath);

        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        $formattedErrors = [];
        foreach ($errors as $error) {
            $formattedErrors[] = [
                'level' => $error->level,
                'code' => $error->code,
                'column' => $error->column,
                'message' => trim($error->message),
                'line' => $error->line,
                'file' => $error->file,
            ];
        }

        return new ValidationResult($isValid, $formattedErrors);
    }

    /**
     * Valida um arquivo XML contra o schema XSD definido na classe.
     *
     * @param string $xmlFilePath Caminho para o arquivo XML.
     * @return ValidationResult
     *
     * @throws \RuntimeException Se o arquivo XML não puder ser lido ou se o schema interno não for encontrado.
     * @throws XmlParseException Se o XML for mal formatado.
     */
    public function validateFile(string $xmlFilePath): ValidationResult
    {
        $xmlContent = file_get_contents($xmlFilePath);
        if ($xmlContent === false) {
            throw new \RuntimeException("Não foi possível ler o arquivo XML: {$xmlFilePath}");
        }
        return $this->validate($xmlContent);
    }
}
