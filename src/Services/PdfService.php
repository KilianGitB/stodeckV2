<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService 
{
    public function generPdfFile($html)
    {
        $domPdf =  new Dompdf();
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Garamond');
        $domPdf->setOptions($pdfOptions);
        $domPdf->loadHtml($html);
        $domPdf->render();
        ob_end_clean();
        $domPdf->stream("invoice.pdf", [
            'Attachement' => false
        ]);
    }
}