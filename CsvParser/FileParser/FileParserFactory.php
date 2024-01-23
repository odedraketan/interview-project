<?php

namespace CsvParser\FileParser;

use CsvParser\OutputHandler\CsvOutputHandler;
use CsvParser\OutputHandler\JsonOutputHandler;
use CsvParser\OutputHandler\OutputHandlerInterface;

class FileParserFactory
{
    public static function createCsvParser(
        string $csvFilePath,
        string $outputFilePath,
        string $outputFormat,
        array $outputHeading,
        array $requiredFields,
        string $delimiter = ''
    ): FileParserInterface {
        $outputHandler = self::createOutputHandler($outputFormat, $outputFilePath);

        return new CsvParser($csvFilePath, $outputHandler, $requiredFields, $delimiter, $outputHeading);
    }

    private static function createOutputHandler(string $outputFormat, string $outputFilePath): OutputHandlerInterface
    {
        switch ($outputFormat) {
            case 'csv':
                return new CsvOutputHandler($outputFilePath);
            case 'json':
                return new JsonOutputHandler($outputFilePath);
            // Add support for other formats as needed
            default:
                echo "Unsupported output format: $outputFormat\n";
                exit(1);
        }
    }
}
