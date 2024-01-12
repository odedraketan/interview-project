<?php

class Parser
{
    private $csvFilePath;
    private $outputFilePath;
    private $outputFormat;
    private $uniqueCombinations = [];
    private $uniqueCombinationsHeadres = ['make', 'model', 'colour', 'capacity', 'network', 'grade', 'condition']; // default
    private $requiredFields = ['brand_name', 'model_name'];
   

    public function __construct($csvFilePath, $outputFilePath, $outputFormat, $outputheading)
    {
        $this->csvFilePath = $csvFilePath;
        $this->outputFilePath = $outputFilePath;
        $this->outputFormat = $outputFormat;
        if(!empty($outputheading)){
            $this->uniqueCombinationsHeadres = $outputheading;
        }
    }

    public function generateUniqueCombinations()
    {
        if (($handle = fopen($this->csvFilePath, "r")) !== FALSE) {
            $delimiter = $this->getDelimiter($handle); // Determine the delimiter dynamically

            $header = fgetcsv($handle, 1000, $delimiter);
            if(count($header) != count($this->uniqueCombinationsHeadres)){
                echo "Error: Headers do not match";
                exit(1);
            }
            
            // Check if required fields are present in the header
            foreach ($this->requiredFields as $requiredField) {
                if (!in_array($requiredField, $header)) {
                    fclose($handle);
                    echo "Error: '$requiredField' is a required field and is not found in the CSV header.\n";
                    exit(1);
                }
            }

            $header = array_map(function ($key) {
                return $this->uniqueCombinationsHeadres[$key] ?? '';
            }, array_keys($header));

            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $row = array_combine($this->uniqueCombinationsHeadres, $data);
                $key = implode('_', array_values(array_intersect_key($row, array_flip($this->uniqueCombinationsHeadres))));

                if (!isset($this->uniqueCombinations[$key])) {
                    $this->uniqueCombinations[$key] = $row + ['count' => 1];
                } else {
                    $this->uniqueCombinations[$key]['count']++;
                }
            }

            fclose($handle);
            $this->uniqueCombinations = array_values($this->uniqueCombinations);
            $this->saveToFile($this->outputFilePath, $this->uniqueCombinations, $this->outputFormat);
        }
    }
    private function getDelimiter($handle)
    {
        $line = fgets($handle);
        rewind($handle);

        $commaCount = substr_count($line, ',');
        $tabCount = substr_count($line, "\t");

        return ($tabCount > $commaCount) ? "\t" : ",";
    }
    private function saveToFile($filePath, $data, $format)
    {
        switch ($format) {
            case 'csv':
                $this->saveToCsv($filePath, $data);
                break;
            case 'json':
                $this->saveToJson($filePath, $data);
                break;
            // Add support for other formats as needed
            default:
                echo "Unsupported output format: $format\n";
                exit(1);
        }
    }

    private function saveToCsv($filePath, $data)
    {
        $handle = fopen($filePath, 'w');

        // Write header
        fputcsv($handle, array_keys($data[0]));

        // Write data
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }

    private function saveToJson($filePath, $data)
    {
        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}


// Check if the correct number of command-line arguments is provided
if ($argv[1] !== '--file' || strpos($argv[4], '--unique-combinations=') !== 0 ||  strpos($argv[3], '--format') !== 0) {
    echo "Usage: php parser.php --file path/to/your/csv/file.csv --format=csv/json --unique-combinations=path/to/output/file \n";
    exit(1);
}

$outputheading = "";
if(isset($argv[5]) && strpos($argv[5], '--fileheading=') == 0){
    $outputheading = str_replace('--fileheading=', '', $argv[5]);
    $outputheading = explode(',', $outputheading);
}

// Get the CSV file path, output file path, and output format from the command-line arguments
$csvFilePath = $argv[2];
$outputFilePath = str_replace('--unique-combinations=', '', $argv[4]);
$outputFormat = str_replace('--format=', '', $argv[3]);

// Create an instance of the CSVParser class
$csvParser = new Parser($csvFilePath, $outputFilePath, $outputFormat, $outputheading);

// Generate unique combinations and save to the specified file in the specified format
$csvParser->generateUniqueCombinations();

echo "Unique combinations saved to: $outputFilePath\n";
