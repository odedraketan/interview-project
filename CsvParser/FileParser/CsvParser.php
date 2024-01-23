<?php

namespace CsvParser\FileParser;

use CsvParser\OutputHandler\OutputHandlerInterface;

class CsvParser implements FileParserInterface
{
    private $csvFilePath;
    private $delimiter;
    private $requiredFields;
    private $outputHandler;
    private $uniqueCombinations = [];
    private $outputHeading = ['make','model','colour','capacity','network','grade','condition'];

    public function __construct(
        string $csvFilePath,
        OutputHandlerInterface $outputHandler,
        array $requiredFields,
        string $delimiter = '',
        array $outputHeading = []
    ) {
        $this->csvFilePath = $csvFilePath;
        $this->outputHandler = $outputHandler;
        $this->requiredFields = $requiredFields;
        $this->delimiter = $delimiter;
        $this->outputHeading = $outputHeading;
    }

    public function parseFile(): void
    {
        if (($handle = fopen($this->csvFilePath, "r")) !== FALSE) {
            $this->delimiter = $this->getDelimiter($handle);
            $header = fgetcsv($handle, 1000, $this->delimiter);
            $this->validateHeader($handle, $header);

            while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {
                $row = array_combine($header, $data);
                $this->processRow($row);
            }

            fclose($handle);
            $this->saveToFile();
        }
    }

    private function validateHeader($handle, array $header): void
    {
        foreach ($this->requiredFields as $requiredField) {
            if (!in_array($requiredField, $header)) {
                fclose($handle);
                echo "Error: '$requiredField' is a required field and is not found in the CSV header.\n";
                exit(1);
            }
        }
    }

    private function processRow(array $row): void
    {
        $key = implode('_', array_values(array_intersect_key($row, array_flip($this->requiredFields))));

        if (!isset($this->uniqueCombinations[$key])) {
            $this->uniqueCombinations[$key] = $row + ['count' => 1];
        } else {
            $this->uniqueCombinations[$key]['count']++;
        }
    }

    private function saveToFile(): void
    {
       
       
        $header = !empty($this->outputHeading) ? $this->outputHeading : array_keys($this->uniqueCombinations[0]);

        $this->outputHandler->writeHeader($header);

        foreach ($this->uniqueCombinations as $row) {
            $this->outputHandler->writeRow($row);
        }

        $this->outputHandler->close();
    }

    private function getDelimiter($handle)
    {
        $line = fgets($handle);
        rewind($handle);

        $delimiters = [',', ';', "\t", '|']; // Common delimiters to check

        foreach ($delimiters as $delimiter) {
            $count = substr_count($line, $delimiter);
            if ($count > 0) {
                return $delimiter;
            }
        }

        // If none of the common delimiters is found, default to comma
        return ',';
    }
}
