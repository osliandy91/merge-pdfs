PdfMerger - PHP Library
This PHP library allows you to merge multiple PDF files efficiently, reducing the final file size using automatic compression with FPDI and Ghostscript.
Requirements
1. Install TCPDF and FPDI
To install FPDI and TCPDF, use Composer: 
```bash
composer require setasign/fpdi-tcpdf
```
2. Install Ghostscript
Ghostscript is used to compress the final PDF after merging. Install it with: 
- On **Linux** (Debian/Ubuntu):
```bash
sudo apt install ghostscript
```
- On **macOS**:
```bash
brew install ghostscript
```
- On **Windows**:
Download and install Ghostscript from [here](https://www.ghostscript.com/download/gsdnld.html).
Usage
1. Include the library
First, include the `PdfMerger.php` class in your project:
```php
require_once 'PdfMerger.php';
```
2. Merge PDFs
To merge the PDFs and get the resulting file, use the following code:
```php
$files = ['file1.pdf', 'file2.pdf', 'file3.pdf']; // List of PDF files to merge
$outputFile = 'merged.pdf'; // Output file name

try {
    $merger = new PdfMerger(); // Create a new instance of PdfMerger
    $finalPdf = $merger->merge($files, $outputFile); // Merge the files
    echo "PDF successfully generated: $finalPdf"; // Success message
} catch (Exception $e) {
    echo "Error: " . $e->getMessage(); // Error handling
}
```
3. Quality Options for Compressing PDFs
The compression process is automatically performed using Ghostscript. You can adjust the compression quality with the `-dPDFSETTINGS` option in the `compressPdf` function:

- **/screen**: High compression, low quality (smallest size).
- **/ebook**: Medium compression, acceptable quality.
- **/printer**: Low compression, good quality.
- **/prepress**: Very low compression, high quality.
4. Ghostscript Configuration
If **Ghostscript** is not in your system’s PATH, you can define the full path in the code:
```php
$gsPath = "/path/to/ghostscript"; // Example: "/usr/bin/gs"
```
Contributions
If you would like to contribute to this project, please fork the repository, create a branch, make your changes, and submit a pull request.
