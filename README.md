# CSV Parser
This PHP script is designed to parse a CSV file, identify unique combinations based on specified fields, and save the results in various output formats (e.g., CSV or JSON).

**Usage**
To use the script, follow these steps:

Clone the repository to your local machine:
  git clone https://github.com/your-username/csv-parser.git

Navigate to the project directory:
  cd csv-parser
  
Run the script with the following command:
  php parser.php --file path/to/your/csv/file.csv --unique-combinations=path/to/output/file --format=csv/json --fileheading=make,model,colour,capacity,network,grade,condition

--file: Path to your CSV file.
--unique-combinations: Path to the output file where unique combinations will be saved.
--format: Output format (csv or json).
--fileheading: Optional. Comma-separated list of headers for the output file. Default headers are 'make', 'model', 'colour', 'capacity', 'network', 'grade', and 'condition'.

**Example**
php parser.php --file data/sample.csv --unique-combinations=output/unique_combinations.csv --format=csv --fileheading=make,model,colour,capacity,network,grade,condition

**Output**

The script will generate a file containing unique combinations based on the specified fields and save it in the specified format.

Unique combinations saved to: output/unique_combinations.csv
