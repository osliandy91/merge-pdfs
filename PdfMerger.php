<?php

require_once 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;

class PdfMerger {
    private $pdf;

    public function __construct() {
        $this->pdf = new Fpdi();
        $this->pdf->SetCompression(true); // Activar compresión
    }

    public function merge($files, $outputFile) {
        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new Exception("El archivo no existe: $file");
            }

            $pageCount = $this->pdf->setSourceFile($file);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $this->pdf->importPage($pageNo);
                $size = $this->pdf->getTemplateSize($templateId);

                // Ajustar orientación
                $orientation = ($size['w'] > $size['h']) ? 'L' : 'P';
                $this->pdf->AddPage($orientation, array($size['w'], $size['h']));
                $this->pdf->useTemplate($templateId);
            }
        }

        // Guardar PDF antes de comprimir
        $this->pdf->Output($outputFile, 'F');

        // Comprimir PDF con Ghostscript
        $compressedFile = str_replace('.pdf', '_compressed.pdf', $outputFile);
        $this->compressPdf($outputFile, $compressedFile);

        return $compressedFile;
    }

    private function compressPdf($inputFile, $outputFile) {
        $gsPath = "gs"; // Asegúrate de que Ghostscript está en el PATH
        $quality = "/ebook"; // Ajusta la calidad según necesites

        $cmd = "$gsPath -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=$quality -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFile $inputFile";
        exec($cmd);

        if (file_exists($outputFile)) {
            unlink($inputFile); // Borrar el archivo grande y mantener el optimizado
        } else {
            throw new Exception("Error al comprimir el PDF con Ghostscript.");
        }
    }
}
