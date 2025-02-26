<?php

require_once 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;

class PdfMerger {
    private $pdf;

    public function __construct() {
        $this->pdf = new Fpdi();
        $this->pdf->SetCompression(true); // Enable compression
    }

    public function merge($files, $outputFile) {
        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new Exception("The file does not exist: $file");
            }

            $pageCount = $this->pdf->setSourceFile($file);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $this->pdf->importPage($pageNo);
                $size = $this->pdf->getTemplateSize($templateId);

                // Adjust orientation
                $orientation = ($size['w'] > $size['h']) ? 'L' : 'P';
                $this->pdf->AddPage($orientation, array($size['w'], $size['h']));
                $this->pdf->useTemplate($templateId);
            }
        }

        // Save the PDF before compression
        $this->pdf->Output($outputFile, 'F');

        // Compress PDF with Ghostscript
        $compressedFile = str_replace('.pdf', '_compressed.pdf', $outputFile);
        $this->compressPdf($outputFile, $compressedFile);

        return $compressedFile;
    }

    private function compressPdf($inputFile, $outputFile) {
        $gsPath = "gs"; // Ensure Ghostscript is in the PATH
        $quality = "/ebook"; // Adjust the quality as needed

        $cmd = "$gsPath -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=$quality -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFile $inputFile";
        exec($cmd);

        if (file_exists($outputFile)) {
            unlink($inputFile); // Delete the large file and keep the optimized one
        } else {
            throw new Exception("Error compressing PDF with Ghostscript.");
        }
    }
}
