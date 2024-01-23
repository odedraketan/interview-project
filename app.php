<?php

require_once __DIR__ . '/vendor/autoload.php';

use CsvParser\FileParser\FileParserFactory;

// Check if the correct number of command-line arguments is provided


if ($argc != 6 || $argv[1] !== '--file' || strpos($argv[3], '--unique-combinations=') !== 0) {
    echo "Usage: php app.php --file path/to/your/csv/file.csv --unique-combinations=path/to/output/file --format=csv/json\n";
    exit(1);
}

$outputHeading = [];
if (strpos($argv[5], '--fileheading=') == 0) {
    $outputHeading = str_replace('--fileheading=', '', $argv[5]);
    $outputHeading = explode(',', $outputHeading);
}

// Get the CSV file path, output file path, and output format from the command-line arguments
$csvFilePath = $argv[2];
$outputFilePath = str_replace('--unique-combinations=', '', $argv[3]);
$outputFormat = str_replace('--format=', '', $argv[4]);

// Create an instance of the FileParser class using the Factory
try{
    $fileParser = FileParserFactory::createCsvParser($csvFilePath, $outputFilePath, $outputFormat, $outputHeading, ['brand_name', 'model_name']);
} catch(Exception $e){
    echo "<pre>";
    print_r($e->getMessage());
    exit;
}

// Generate unique combinations and save to the specified file in the specified format
$fileParser->parseFile();

echo "Unique combinations saved to: $outputFilePath\n";
